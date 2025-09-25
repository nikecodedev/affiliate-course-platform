<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Commission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.financial.reports');
    }

    public function revenueReport(Request $request)
    {
        $data = Invoice::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(final_amount) as total'))
            ->where('status', 'confirmed')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }

    public function refundReport(Request $request)
    {
        $data = Invoice::select(DB::raw('DATE(refunded_at) as date'), DB::raw('SUM(final_amount) as total'))
            ->where('status', 'refunded')
            ->groupBy(DB::raw('DATE(refunded_at)'))
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }

    public function commissionReport(Request $request)
    {
        $outstanding = Commission::where('status', 'pending')->sum('amount');
        $paid = Commission::where('status', 'paid')->sum('amount');

        return response()->json([
            'outstanding' => (float) $outstanding,
            'paid' => (float) $paid,
        ]);
    }

    public function topSalespeopleReport(Request $request)
    {
        $period = $request->get('period', 'day');
        $dateExpr = match ($period) {
            'day' => 'DATE(created_at)',
            'week' => 'YEARWEEK(created_at, 1)',
            'month' => 'DATE_FORMAT(created_at, "%Y-%m")',
            default => 'DATE(created_at)',
        };

        $data = Invoice::select(DB::raw("{$dateExpr} as period"), 'user_id', DB::raw('SUM(final_amount) as total'))
            ->where('status', 'confirmed')
            ->groupBy('period', 'user_id')
            ->orderBy('total', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($row) {
                $row->user = User::select('id', 'name')->find($row->user_id);
                return $row;
            });

        return response()->json($data);
    }
}


