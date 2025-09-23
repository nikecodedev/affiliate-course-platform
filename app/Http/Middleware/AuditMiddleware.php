<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only audit admin actions
        if (Auth::guard('admin')->check() && $this->shouldAudit($request)) {
            $this->logAudit($request, $response);
        }

        return $response;
    }

    /**
     * Determine if the request should be audited
     */
    private function shouldAudit(Request $request): bool
    {
        $method = $request->method();
        $path = $request->path();

        // Only audit admin routes
        if (!str_starts_with($path, 'admin')) {
            return false;
        }

        // Audit specific actions
        $auditActions = ['POST', 'PUT', 'PATCH', 'DELETE'];
        if (!in_array($method, $auditActions)) {
            return false;
        }

        // Skip certain routes
        $skipRoutes = [
            'admin/settings/upload-logo',
            'admin/settings/upload-background',
            'admin/stats',
            'admin/chart-data',
        ];

        foreach ($skipRoutes as $route) {
            if (str_starts_with($path, $route)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Log the audit information
     */
    private function logAudit(Request $request, Response $response): void
    {
        $admin = Auth::guard('admin')->user();
        $method = $request->method();
        $path = $request->path();

        // Determine action based on method and path
        $action = $this->determineAction($method, $path);

        // Get model information if available
        $modelInfo = $this->getModelInfo($path, $request);

        // Create audit log
        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => $action,
            'model_type' => $modelInfo['model_type'] ?? null,
            'model_id' => $modelInfo['model_id'] ?? null,
            'old_values' => $modelInfo['old_values'] ?? null,
            'new_values' => $modelInfo['new_values'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Determine the action based on method and path
     */
    private function determineAction(string $method, string $path): string
    {
        if ($method === 'POST' && str_contains($path, '/create')) {
            return 'created';
        }

        if (in_array($method, ['PUT', 'PATCH']) && str_contains($path, '/edit')) {
            return 'updated';
        }

        if ($method === 'DELETE') {
            return 'deleted';
        }

        if ($method === 'POST' && str_contains($path, '/confirm')) {
            return 'confirmed';
        }

        if ($method === 'POST' && str_contains($path, '/refund')) {
            return 'refunded';
        }

        return 'modified';
    }

    /**
     * Get model information from the request
     */
    private function getModelInfo(string $path, Request $request): array
    {
        $segments = explode('/', $path);
        
        // Extract model type from path
        if (count($segments) >= 2) {
            $modelType = $segments[1];
            
            // Map admin routes to model types
            $modelMap = [
                'plans' => Plan::class,
                'courses' => Course::class,
                'sales' => Sale::class,
                'users' => User::class,
                'settings' => SystemSetting::class,
                'expenses' => Expense::class,
                'withdrawals' => Withdrawal::class,
                'commission-payments' => CommissionPayment::class,
            ];

            if (isset($modelMap[$modelType])) {
                return [
                    'model_type' => $modelMap[$modelType],
                    'model_id' => $request->route($modelType) ? $request->route($modelType)->id : null,
                    'new_values' => $request->except(['_token', '_method']),
                ];
            }
        }

        return [];
    }
}

