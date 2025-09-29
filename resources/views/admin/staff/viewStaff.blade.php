@extends('layouts.admin.layout')

@section('title', 'Staff Information - Letty\'s Birthing Home')
@section('page-title', 'Staff Management')

@section('content')

        <div class="container-fluid main-content">
            <div class="form-card">
                <div class="form-header">
                    <h5 id="pageTitle">
                        <i class="fas fa-eye me-2"></i> Staff Details - {{ $staff->staff_id }}
                    </h5>
                    <div class="header-actions">
                        <a href="{{ route('staffs') }}" class="back-button">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <button class="edit-button" id="editToggleBtn" onclick="toggleEditMode()">
                            <i class="fas fa-edit me-2"></i>
                            <span id="editBtnText">Edit</span>
                        </button>
                    </div>
                </div>

                <div class="alert alert-warning" id="editModeAlert" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span>You are now in edit mode. Make your changes and click "Update Staff" to save.</span>
                </div>

              <form id="editStaffForm" method="POST" action="{{ route('updateStaff', $staff->id) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    <!-- PERSONAL INFORMATION -->
    <div class="form-section">
        <div class="form-section-title"><i class="fas fa-user me-2"></i> Personal Information</div>

        <div class="form-row">
            <div class="form-group">
                <label>Staff ID</label>
                <input type="text" class="form-control" value="{{ $staff->staff_id }}" readonly>
            </div>
            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select class="form-select" name="status" disabled required>
                    <option value="active" {{ $staff->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $staff->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="on-leave" {{ $staff->status == 'on-leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="first_name" value="{{ $staff->first_name }}" readonly required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" name="last_name" value="{{ $staff->last_name }}" readonly required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="{{ $staff->user->email ?? '' }}" readonly required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="tel" class="form-control" name="phone" value="{{ $staff->phone }}" readonly required pattern="09[0-9]{9}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" class="form-control" name="date_of_birth" value="{{ $staff->date_of_birth }}" readonly required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select class="form-select" name="gender" disabled required>
                    <option value="male" {{ $staff->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $staff->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $staff->gender == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea class="form-textarea" name="address" rows="3" readonly required>{{ $staff->address }}</textarea>
        </div>
    </div>

    <!-- EMPLOYMENT INFORMATION -->
    <div class="form-section">
        <div class="form-section-title"><i class="fas fa-briefcase me-2"></i> Employment</div>
        <div class="form-group">
            <label>Branch</label>
            <select class="form-select" name="branch_id" disabled required>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $staff->branch_id == $branch->id ? 'selected' : '' }}>
                        {{ $branch->branch_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- SCHEDULE INFORMATION -->
    <div class="form-section">
        <div class="form-section-title"><i class="fas fa-clock me-2"></i> Schedule</div>

        <div class="form-group">
            <label>Shift</label>
            <select class="form-select" id="shift" name="shift" disabled required>
                <option value="day" {{ $staff->workDays->first()->shift == 'day' ? 'selected' : '' }}>Day</option>
                <option value="night" {{ $staff->workDays->first()->shift == 'night' ? 'selected' : '' }}>Night</option>
                <option value="regular" {{ $staff->workDays->first()->shift == 'regular' ? 'selected' : '' }}>Regular</option>
            </select>
        </div>

        <div class="form-group">
            <label>Work Days</label>
            <div class="checkbox-group">
                @php $workDays = $staff->workDays->pluck('day')->toArray(); @endphp
                @foreach (['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                    <div class="checkbox-item">
                        <input type="checkbox" class="readonlyMode" id="{{ $day }}" name="workDays[]" value="{{ $day }}" {{ in_array($day, $workDays) ? 'checked' : '' }} disabled>
                        <label for="{{ $day }}">{{ ucfirst($day) }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="form-section form-actions" id="formActions" style="display: none;">
        <button type="submit" class="btnn btn-primary">
            <i class="fas fa-save me-2"></i> Update Staff
        </button>
    </div>
</form>

            </div>
        </div>
    </main>
 <script src="{{ asset('script/admin/view-staff.js') }}"></script>
@endsection

   