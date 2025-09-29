@extends('layouts.admin.layout')

@section('title', 'Admin Profile - Letty\'s Birthing Home')
@section('page-title', 'Admin Profile')

@section('content')

    <div class="container-fluid main-content">
        <div class="form-card">
            <div class="form-header">
                <h5 id="formTitle">
                    <i class="fas fa-user-circle me-2"></i> My Profile
                </h5>
                <div class="header-actions">
                    <button class="edit-button" id="editProfileBtn" onclick="toggleEditMode()">
                        <i class="fas fa-edit me-2"></i> <span id="editBtnText">Edit Profile</span>
                    </button>
                </div>
            </div>

            <div class="alert alert-warning" id="editModeAlert" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span>You are now in edit mode. Make your changes and click "Save Changes" to save.</span>
            </div>

            <!-- Profile Information -->
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-user me-2"></i> Profile Information</div>
                <div style="text-align: center; margin-bottom: 20px;">
                    <div class="profile-avatar" style="position: relative; display: inline-block;">
                        <img src="{{ $admin && $admin->avatar_path ? asset($admin->avatar_path) : asset('img/adminProfile.jpg') }}"
                            alt="Profile" class="img-fluid rounded shadow-sm" style="max-width: 150px;">
                        <div id="avatarUploadBtn" onclick="uploadAvatar()"
                            style="display: none; position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.6); color: #fff; padding: 5px; border-radius: 50%; cursor: pointer;">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                </div>


            </div>

            <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Contact Information -->
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-info-circle me-2"></i> Contact Information
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name <span class="required">*</span></label>
                            <input type="text" name="first_name" id="firstName" class="form-control"
                                value="{{ optional($admin)->first_name ?? '' }}" readonly required>
                            <div class="invalid-feedback">First Name is required</div>
                        </div>
                        <div class="form-group">
                            <label>Last Name <span class="required">*</span></label>
                            <input type="text" name="last_name" id="lastName" class="form-control"
                                value="{{ optional($admin)->last_name ?? '' }}" readonly required>
                            <div class="invalid-feedback">Last Name is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $user->email }}" readonly required>
                            <div class="invalid-feedback">Valid email is required</div>
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone" id="phone" class="form-control"
                                value="{{ optional($admin)->phone ?? '' }}" readonly required pattern="^\+?\d{10,15}$">
                            <div class="invalid-feedback">Valid phone number is required</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address <span class="required">*</span></label>
                        <input type="text" name="address" id="address" class="form-control"
                            value="{{ optional($admin)->address ?? '' }}" readonly required>
                        <div class="invalid-feedback">Address is required</div>
                    </div>
                </div>


                <div class="form-section form-actions" id="formActions" style="display: none;">
                    <button type="submit" class="btnn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                    <button type="button" class="btnn btn-secondary" onclick="cancelEdit()">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                </div>
            </form>

            <!-- Security Section -->
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-key me-2"></i> Security</div>
                <button type="button" class="btnn btn-danger" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-key me-2"></i> Change Password
                </button>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div class="toast-container">
            @if (session('message') && session('type'))
                <div class="toast {{ session('type') === 'success' ? 'toast-success' : 'toast-error' }}" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">{{ session('type') === 'success' ? 'Success' : 'Error' }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('message') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    </main>

    <!-- Avatar Upload Form -->
    <form id="avatarForm" action="{{ route('admin.updateAvatar') }}" method="POST" enctype="multipart/form-data"
        style="display: none;">
        @csrf
        <input type="file" name="avatar" id="avatarInputHidden" accept="image/*" onchange="submitAvatarForm()">
    </form>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" method="POST" action="{{ route('admin.changePassword') }}"
                        class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password <span
                                    class="required">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="currentPassword" name="current_password"
                                    required>
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    data-target="currentPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Current Password is required</div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password <span
                                    class="required">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" name="new_password"
                                    required>
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    data-target="newPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordLengthError" class="text-danger small mt-1" style="display: none;">
                                Password must be at least 8 characters long
                            </div>
                            @error('new_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-3">
                            <label for="newPasswordConfirmation" class="form-label">Confirm New Password <span
                                    class="required">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPasswordConfirmation"
                                    name="new_password_confirmation" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    data-target="newPasswordConfirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordMismatch" class="text-danger small mt-1" style="display: none;">
                                Passwords do not match
                            </div>
                            @error('new_password_confirmation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="changePasswordForm"
                        id="changePasswordSubmitBtn">
                        <i class="fas fa-save me-2"></i> Save Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('script/admin/profile.js') }}"></script>
@endsection
