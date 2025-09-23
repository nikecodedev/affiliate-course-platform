<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\Admin;

class AuditController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('admin');

        // Filter by admin
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(20);
        $admins = Admin::all();
        
        $actions = [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login' => 'Login',
            'logout' => 'Logout',
            'failed_login' => 'Failed Login',
            'password_changed' => 'Password Changed',
            'confirmed' => 'Confirmed',
            'refunded' => 'Refunded',
        ];

        $modelTypes = [
            'App\\Models\\Plan' => 'Plans',
            'App\\Models\\Course' => 'Courses',
            'App\\Models\\Sale' => 'Sales',
            'App\\Models\\User' => 'Users',
            'App\\Models\\SystemSetting' => 'Settings',
            'App\\Models\\Expense' => 'Expenses',
            'App\\Models\\Withdrawal' => 'Withdrawals',
            'App\\Models\\CommissionPayment' => 'Commission Payments',
        ];

        return view('admin.audit.index', compact(
            'auditLogs', 
            'admins', 
            'actions', 
            'modelTypes'
        ));
    }

    /**
     * Display audit statistics
     */
    public function statistics()
    {
        $stats = [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::whereDate('created_at', today())->count(),
            'failed_logins' => AuditLog::where('action', 'failed_login')->count(),
            'admin_actions' => AuditLog::whereNotNull('admin_id')->count(),
        ];

        // Most active admins
        $activeAdmins = Admin::selectRaw('admins.*, COUNT(audit_logs.id) as log_count')
            ->leftJoin('audit_logs', 'admins.id', '=', 'audit_logs.admin_id')
            ->groupBy('admins.id')
            ->orderBy('log_count', 'desc')
            ->limit(10)
            ->get();

        // Recent activity
        $recentActivity = AuditLog::with('admin')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Activity by day (last 30 days)
        $dailyActivity = AuditLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.audit.statistics', compact(
            'stats', 
            'activeAdmins', 
            'recentActivity', 
            'dailyActivity'
        ));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('admin');

        // Apply same filters as index
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Date',
                'Admin',
                'Action',
                'Model Type',
                'Model ID',
                'IP Address',
                'User Agent'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->admin ? $log->admin->name : 'System',
                    $log->formatted_action,
                    class_basename($log->model_type ?? ''),
                    $log->model_id,
                    $log->ip_address,
                    $log->user_agent
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

