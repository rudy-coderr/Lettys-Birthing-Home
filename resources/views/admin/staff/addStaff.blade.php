@extends('layouts.admin.layout')

@section('title', 'Add New Staff - Letty\'s Birthing Home')
@section('page-title', 'Staff Management')

@section('content')

    <div class="container-fluid main-content">
        <div class="form-card">
            <div class="form-header">
                <h5>
                    <i class="fas fa-user-plus me-2"></i>
                    Add New Midwife
                </h5>
                <div class="header-actions">
                    <a href="{{ route('staffs') }}" class="back-button">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                </div>
            </div>

            <form id="addStaffForm" action="{{ route('addStaff') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                {{-- Personal Info --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-user me-2"></i> Personal Information</div>

                    <div class="form-row">
                       
                        <div class="form-group">
                            <label for="status">Status <span class="required">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="" disabled>Select status</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="on-leave" {{ old('status') == 'on-leave' ? 'selected' : '' }}>On Leave
                                </option>
                            </select>
                            <div class="invalid-feedback">Status is required</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" name="firstName" id="firstName" class="form-control"
                                value="{{ old('firstName') }}" required>
                            <div class="invalid-feedback">First Name is required</div>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" name="lastName" id="lastName" class="form-control"
                                value="{{ old('lastName') }}" required>
                            <div class="invalid-feedback">Last Name is required</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email') }}" required>
                            <div class="invalid-feedback">Email is required</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone" id="phone" class="form-control"
                                value="{{ old('phone') }}" pattern="09[0-9]{9}" required>
                            <div class="invalid-feedback">Phone Number is required (e.g., 09123456789)</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dateOfBirth">Date of Birth <span class="required">*</span></label>
                            <input type="date" name="dateOfBirth" id="dateOfBirth" class="form-control"
                                value="{{ old('dateOfBirth') }}" required>
                            <div class="invalid-feedback">Date of Birth is required</div>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender <span class="required">*</span></label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="" disabled>Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <div class="invalid-feedback">Gender is required</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address <span class="required">*</span></label>
                        <textarea name="address" id="address" class="form-textarea" rows="3" required>{{ old('address') }}</textarea>
                        <div class="invalid-feedback">Address is required</div>
                    </div>
                </div>

                {{-- Employment Info --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-briefcase me-2"></i> Employment Information</div>

                    <div class="form-group">
                        <label for="branch_id">Branch <span class="required">*</span></label>
                        <select name="branch_id" id="branch_id" class="form-select" required>
                            <option value="" disabled>Select branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Branch is required</div>
                    </div>
                </div>

                {{-- Schedule Info --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-clock me-2"></i> Schedule Information</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="shift">Shift <span class="required">*</span></label>
                            <select name="shift" id="shift" class="form-select" required>
                                <option value="" disabled>Select shift</option>
                                <option value="day" {{ old('shift') == 'day' ? 'selected' : '' }}>Day (7:00 AM - 7:00
                                    PM)</option>
                                <option value="night" {{ old('shift') == 'night' ? 'selected' : '' }}>Night (7:00 PM -
                                    7:00 AM)</option>
                            </select>
                            <div class="invalid-feedback">Shift is required</div>
                        </div>

                        <div class="form-group">
                            <label>Work Days <span class="required">*</span></label>
                            <div class="checkbox-group">
                                @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="workDays[]" id="{{ $day }}"
                                            value="{{ $day }}"
                                            {{ is_array(old('workDays')) && in_array($day, old('workDays')) ? 'checked' : '' }}>
                                        <label for="{{ $day }}">{{ ucfirst(substr($day, 0, 3)) }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="invalid-feedback" id="workDaysError">At least one work day is required</div>
                        </div>
                    </div>
                </div>

                <div class="form-section form-actions">
                    <button type="reset" class="btn btn-outline-secondary"><i class="fas fa-undo me-2"></i>
                        Reset</button>
                    <button type="submit" class="btnn btn-primary"><i class="fas fa-save me-2"></i> Add Staff
                        Member</button>
                </div>
            </form>

        </div>
    </div>
    </main>
    <script src="{{ asset('script/admin/add-staff.js') }}"></script>
@endsection
