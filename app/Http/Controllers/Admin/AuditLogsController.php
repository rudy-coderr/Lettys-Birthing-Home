<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AuditLogsController extends Controller
{
    public function index()
    {
        $auditLogs = AuditLog::with('staff')
            ->latest()
            ->get()
            ->map(function ($log) {
                return [
                    'staff_name' => $log->staff
                    ? $log->staff->first_name . ' ' . $log->staff->last_name
                    : 'System',
                    'action'     => $log->action,
                    'date'       => $log->created_at->format('Y-m-d H:i'),
                    'details'    => $log->details,
                ];
            });

        return view('admin.auditlogs.audit-logs', compact('auditLogs'));
    }
}
