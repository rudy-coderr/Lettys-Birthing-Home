<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\EmergencyController as AdminEmergencyController;
use App\Http\Controllers\Admin\ManageStaffController;
use App\Http\Controllers\Admin\MedicationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Staff\AllAppointmentController;
use App\Http\Controllers\Staff\EmergencyController as StaffEmergencyController;
use App\Http\Controllers\Staff\NotificationController as StaffNotificationController;
use App\Http\Controllers\Staff\PatientManagementController;
use App\Http\Controllers\Staff\PatientMedicationController;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Staff\ScheduleController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [AuthController::class, 'auth']);
Route::get('/login', [AuthController::class, 'auth'])->name('login');
Route::post('/login', [AuthController::class, 'process'])->name('login.process');


Route::get('/forgot-password', [ResetPasswordController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Verify Code
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::get('/2fa/verify', [TwoFactorController::class, 'show'])->name('2fa.verify');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('verify.2fa.submit');
    Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');
});

// Authenticated Routes
Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    // Admin Routes (Only for admin users)
    Route::middleware(['role:admin'])->group(function () {

        Route::get('/admin/emergencies', [AdminEmergencyController::class, 'index'])
            ->name('admin.emergencies');
        Route::delete('/admin/emergency/{id}', [AdminEmergencyController::class, 'destroy'])
            ->name('admin.emergency.delete');
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/setting', [AdminController::class, 'setting'])->name('setting');

        // Manage Staff
        Route::get('/staffs', [ManageStaffController::class, 'staffs'])->name('staffs');
        Route::get('/viewStaffs/{id}', [ManageStaffController::class, 'viewStaffs'])->name('viewStaffs');
        Route::get('/addStaffs', [ManageStaffController::class, 'addStaffs'])->name('addStaffs');
        Route::put('/updateStaff/{id}', [ManageStaffController::class, 'updateStaff'])->name('updateStaff');
        Route::post('/addStaff', [ManageStaffController::class, 'storeStaff'])->name('addStaff');
        Route::delete('/admin/staffs/{id}', [ManageStaffController::class, 'deleteStaff'])->name('staffs.delete');
        Route::put('/admin/staffs/{id}/update-schedule', [ManageStaffController::class, 'updateSchedule'])->name('staffs.updateSchedule');
        Route::put('/staffs/{id}/toggle-active', [ManageStaffController::class, 'toggleActive'])->name('staffs.toggleActive');

        // Admin Profile
        Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('adminProfile');
        Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
        Route::post('/admin/update-avatar', [AdminController::class, 'updateAvatar'])->name('admin.updateAvatar');
        Route::put('/admin/change-password', [AdminController::class, 'changePassword'])->name('admin.changePassword');

        // Patient Management
        Route::get('/admin/currentPatients', [PatientController::class, 'currentPatients'])
            ->name('admin.currentPatients');
        Route::get('/admin/all-patient-records', [PatientController::class, 'patientRecords'])
            ->name('admin.patientRecords');
        Route::get('/admin/patient/{id}/pdf-records', [PatientController::class, 'patientPdfRecords'])
            ->name('patientPdfRecords');

        Route::get('/admin/prenatals', [PatientController::class, 'prenatals'])->name('prenatals');

        Route::get('/admin/patient/pdf/{record}/view', [PatientController::class, 'pdfRecord'])
            ->name('admin.pdfRecord');
        Route::get('/admin/patient/{patient}/records/{record}/download', [PatientController::class, 'downloadRecord'])
            ->name('admin.downloadRecord');

        // Appointments
        Route::get('/admin/appointments', [AppointmentController::class, 'todaysAppointments'])
            ->name('admin.appointments');

        Route::get('/admin/addAppointment', [AppointmentController::class, 'addAppointment'])->name('admin.addAppointment');
        Route::post('/admin/addAppointment/store', [AppointmentController::class, 'storeAppointment'])
            ->name('admin.storeAppointment');

        Route::get('/admin/allAppointments', [AppointmentController::class, 'allAppointments'])
            ->name('admin.allAppointments');

        Route::post('/admin/appointment/cancel', [AppointmentController::class, 'cancelAppointment'])
            ->name('admin.cancelAppointment');
        Route::post('/admin/appointment/reschedule', [AppointmentController::class, 'rescheduleAppointment'])
            ->name('admin.appointment.reschedule');
        Route::delete('/admin/appointment/delete', [AppointmentController::class, 'destroyAppointment'])
            ->name('admin.appointment.delete');
        Route::get('/admin/appointments/{id}/edit', [AppointmentController::class, 'editAppointment'])
            ->name('admin.editAppointment');
        Route::put('/admin/appointments/{id}', [AppointmentController::class, 'updateAppointment'])
            ->name('admin.updateAppointment');

        // Inventory
        Route::get('/admin/medicines', [MedicationController::class, 'medicines'])->name('admin.inventory.medicines');
        Route::get('/medicines/create', [MedicationController::class, 'createMedicine'])->name('medicines.create');
        Route::post('/admin/medicines/store', [MedicationController::class, 'storeMedicine'])->name('medicines.store');
        Route::get('/admin/vaccines', [MedicationController::class, 'vaccines'])->name('admin.inventory.vaccines');
        Route::get('/admin/supplies', [MedicationController::class, 'supplies'])->name('admin.inventory.supplies');
        Route::get('/admin/vaccines/create', [MedicationController::class, 'createVaccine'])->name('vaccines.create');
        Route::post('/admin/vaccines/store', [MedicationController::class, 'storeVaccine'])->name('vaccines.store');
        Route::get('/admin/supplies/create', [MedicationController::class, 'createSupply'])->name('supplies.create');
        Route::post('/admin/supplies/store', [MedicationController::class, 'storeSupply'])->name('supplies.store');
        Route::put('/medicines/{id}/restock', [MedicationController::class, 'restock'])->name('medicines.restock');
        Route::put('/admin/vaccines/{id}/restock', [MedicationController::class, 'restockVaccine'])
            ->name('vaccines.restock');
        Route::put('/admin/supplies/{id}/restock', [MedicationController::class, 'restockSupply'])
            ->name('supplies.restock');
        Route::delete('/admin/supplies/{id}', [MedicationController::class, 'deleteSupply'])
            ->name('supplies.delete');
        Route::delete('/vaccines/{id}', [MedicationController::class, 'deleteVaccine'])->name('vaccines.destroy');
        Route::delete('/medicines/{id}', [MedicationController::class, 'deleteMedicine'])->name('medicines.destroy');
        Route::get('/admin/medicines/{id}', [MedicationController::class, 'showMedicine'])->name('admin.medicines.show');
        Route::put('/admin/medicines/{id}', [MedicationController::class, 'updateMedicine'])->name('admin.medicines.update');
        Route::get('/admin/vaccines/{id}', [MedicationController::class, 'showVaccine'])->name('vaccines.show');
        Route::put('/admin/vaccines/{id}', [MedicationController::class, 'updateVaccine'])->name('vaccines.update');
        Route::get('/admin/supplies/{id}', [MedicationController::class, 'showSupply'])->name('supplies.show');
        Route::put('/admin/supplies/{id}', [MedicationController::class, 'updateSupply'])->name('supplies.update');
        Route::post('/admin/units/store', [MedicationController::class, 'storeUnit'])->name('units.store');

        Route::get('/admin/reports', [ReportsController::class, 'reports'])->name('reports');

        Route::get('/admin/audit-logs', [AuditLogsController::class, 'index'])
            ->name('admin.audit-logs');
        // Admin Notifications
        Route::prefix('admin/notifications')->name('admin.notifications.')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'fetchAppointments'])->name('index');
            Route::get('/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
            Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        });

    });

    // Staff Routes (Only for staff users)
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');

        // Staff Profile
        Route::get('/staff/profile', [ProfileController::class, 'staffProfile'])->name('staffProfile');
        Route::put('/staff/profile/update', [ProfileController::class, 'updateProfile'])->name('staff.profile.update');
        Route::post('/staff/update-avatar', [ProfileController::class, 'updateAvatar'])->name('staff.updateAvatar');
        Route::put('/staff/change-password', [ProfileController::class, 'changePassword'])->name('staff.changePassword');

        // Schedule
        Route::get('/staff/schedule', [ScheduleController::class, 'schedules'])->name('schedules');
        Route::get('/schedule/events', [ScheduleController::class, 'scheduleEvents'])->name('staff.schedule.events');

        // Appointments
        Route::get('/staff/todaysAppointments', [AllAppointmentController::class, 'todaysAppointments'])->name('todaysAppointments');
        Route::get('/staff/pendingAppointment', [AllAppointmentController::class, 'pendingAppointment'])->name('pendingAppointment');
        Route::get('/staff/addAppointment', [AllAppointmentController::class, 'addAppointment'])->name('addAppointment');
        Route::post('/staff/addAppointment/store', [AllAppointmentController::class, 'storeAppointment'])->name('storeAppointment');
        Route::get('/staff/checkups/{id?}', [AllAppointmentController::class, 'checkups'])->name('checkups');
        Route::post('/staff/prenatal/store', [AllAppointmentController::class, 'storePrenatal'])->name('prenatal.store');
        Route::post('/staff/appointment/reschedule', [AllAppointmentController::class, 'rescheduleAppointment'])->name('appointment.reschedule');
        Route::delete('/staff/appointment/delete', [AllAppointmentController::class, 'destroyAppointment'])
            ->name('deleteAppointment');
        Route::post('/staff/appointment/cancel', [AllAppointmentController::class, 'cancelAppointment'])
            ->name('cancelAppointment');
        Route::get('/staff/appointments/{id}/edit', [AllAppointmentController::class, 'editAppointment'])->name('editAppointment');
        Route::put('/staff/appointments/{id}', [AllAppointmentController::class, 'updateAppointment'])->name('updateAppointment');

        Route::get('/staff/appointment/add/{client_id?}',
            [AllAppointmentController::class, 'addAppointment']
        )->name('staff.addAppointment');

        Route::get('/staff/patient/{client}/edit', [PatientManagementController::class, 'edit'])->name('patient.edit');
        Route::patch('/staff/patient/{client}', [PatientManagementController::class, 'update'])->name('patient.update');
        Route::get('/patients/complete', [PatientManagementController::class, 'completeVisits'])
            ->name('completeVisits');
        Route::get('/staff/currentPatients', [PatientManagementController::class, 'currentPatients'])->name('currentPatients');
        Route::get('/staff/patient/{id}/pdf-records', [PatientManagementController::class, 'patientPdfRecords'])
            ->name('patient.pdfRecords');
        Route::get('/patients/{patient}/records/{record}/download', [PatientManagementController::class, 'downloadRecord'])
            ->name('downloadRecord');

        Route::get('/patients/records/{record}/view', [PatientManagementController::class, 'pdfRecord'])
            ->name('pdfRecord');
        Route::get('/patients/{id}/edit-latest', [PatientManagementController::class, 'editLatestVisit'])
            ->name('patient.editLatestVisit');
// Update the record
        Route::put('/patients/{id}/update-latest', [PatientManagementController::class, 'updateLatestVisit'])
            ->name('patient.updateLatestVisit');

        Route::get('/patients/add', [PatientManagementController::class, 'addPatient'])->name('addPatient');
        Route::post('/patients/store', [PatientManagementController::class, 'storePatientRecord'])->name('storePatientRecord');

        Route::get('/staff/patientRecords', [PatientManagementController::class, 'patientRecords'])->name('patientRecords');
        Route::get('/staff/patient/{id}/addRecords', [PatientManagementController::class, 'addRecords'])->name('addRecords');
        Route::post('/staff/patient/{id}/prenatal/store', [PatientManagementController::class, 'storePrenatal'])->name('patient.prenatal.store');

        Route::get('/staff/patient/{id}/records', [PatientManagementController::class, 'recordCheckup'])->name('recordCheckup');
        Route::get('/staff/patient/{id}/visit/{visitNumber}', [PatientManagementController::class, 'fetchVisit'])->name('fetchVisit');

        Route::get('/staff/patient/{id}/download-pdf/{visitNumber}', [PatientManagementController::class, 'downloadPdf'])->name('patient.downloadPdf');

        Route::get('/intrapartum/{patient}', [PatientManagementController::class, 'startIntrapartum'])
            ->name('intrapartumAssessment');

        Route::post('intrapartum/{patient}/store', [PatientManagementController::class, 'storeIntrapartum'])
            ->name('intrapartum.store');

        Route::get('intrapartum/{delivery}/cancel', [PatientManagementController::class, 'cancelDelivery'])
            ->name('intrapartum.cancel');
        Route::get('postpartum/{delivery}', [PatientManagementController::class, 'startPostpartum'])
            ->name('postpartumStage');
        Route::post('postpartum/{delivery}/store', [PatientManagementController::class, 'storePostpartum'])
            ->name('postpartum.store');
        Route::get('baby-registration/{delivery}', [PatientManagementController::class, 'startBabyRegistration'])
            ->name('babyRegistration');
        Route::post('/baby-registration/{deliveryId}', [PatientManagementController::class, 'storeBabyRegistration'])
            ->name('storeBabyRegistration');

        Route::get('/staff/patient/{id}/postnatal-care', [PatientManagementController::class, 'postnatalCare'])
            ->name('patient.postnatalCare');

        Route::get('/patient-medication', [PatientMedicationController::class, 'index'])
            ->name('patientMedication');
        Route::post('/patient-medication/store', [PatientMedicationController::class, 'store'])->name('patientMedication.store');
        Route::get('/staff/patient-medication/history', [PatientMedicationController::class, 'history'])
            ->name('patientMedication.history');
        Route::put('/patient-medication/{id}', [PatientMedicationController::class, 'update'])->name('patientMedication.update');

        Route::get('/intrapartum/{record}/edit', [PatientManagementController::class, 'editIntrapartum'])->name('editIntrapartum');
        Route::put('/intrapartum/{record}', [PatientManagementController::class, 'updateIntrapartum'])->name('updateIntrapartum');
        Route::get('/postpartum/{record}/edit', [PatientManagementController::class, 'editPostpartum'])->name('editPostpartum');
        Route::put('/postpartum/{recordId}', [PatientManagementController::class, 'updatePostpartum'])->name('updatePostpartum');
        Route::get('/prenatal/{record}/edit', [PatientManagementController::class, 'editPrenatal'])->name('editPrenatal');
        Route::put('/prenatal/{record}', [PatientManagementController::class, 'updatePrenatal'])->name('updatePrenatal');
        Route::put('/registration/{record}', [PatientManagementController::class, 'updateBabyRegistration'])
            ->name('updateRegistration');
        Route::get('/registration/{record}/edit', [PatientManagementController::class, 'editRegistration'])->name('editRegistration');

        Route::get('/staff/emergencies', [StaffEmergencyController::class, 'index'])
            ->name('staff.emergencies');
        Route::delete('/staff/emergency/{id}', [StaffEmergencyController::class, 'destroy'])
            ->name('staff.emergency.delete');

        // Staff Notifications
        Route::prefix('staff/notifications')->name('staff.notifications.')->group(function () {
            Route::get('/', [StaffNotificationController::class, 'fetchAppointments'])->name('index');
            Route::get('/{id}/read', [StaffNotificationController::class, 'markAsRead'])->name('read');
            Route::post('/mark-all-read', [StaffNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        });

    });

});
