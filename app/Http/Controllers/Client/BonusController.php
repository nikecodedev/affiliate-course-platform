<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BonusPayment;
use App\Models\ReferralNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    /**
     * Display bonus overview
     */
    public function index(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        // Get bonus statistics
        $statistics = $this->getBonusStatistics($client);
        
        // Get recent bonus payments
        $recentBonuses = BonusPayment::where('client_id', $client->id)
            ->with(['sale', 'fromClient'])
            ->latest()
            ->limit(10)
            ->get();
        
        return view('client.bonus.index', compact('statistics', 'recentBonuses'));
    }

    /**
     * Display detailed bonus payments
     */
    public function payments(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $query = BonusPayment::where('client_id', $client->id)
            ->with(['sale', 'fromClient', 'bonusConfiguration']);

        // Filter by bonus type
        if ($request->filled('bonus_type')) {
            $query->where('bonus_type', $request->bonus_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bonusPayments = $query->latest()->paginate(20);

        return view('client.bonus.payments', compact('bonusPayments'));
    }

    /**
     * Display detailed bonus statistics
     */
    public function statistics(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        // Get comprehensive statistics
        $statistics = $this->getDetailedBonusStatistics($client);
        
        // Get bonus payments for chart data
        $chartData = $this->getBonusChartData($client, $request);
        
        // Get bonus type breakdown
        $bonusTypeBreakdown = $this->getBonusTypeBreakdown($client);
        
        // Get monthly earnings for the last 12 months
        $monthlyEarnings = $this->getMonthlyEarnings($client);
        
        return view('client.bonus.statistics', compact('statistics', 'chartData', 'bonusTypeBreakdown', 'monthlyEarnings'));
    }

    /**
     * Get chart data for bonus statistics
     */
    public function getChartData(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $chartData = $this->getBonusChartData($client, $request);
        
        return response()->json($chartData);
    }

    /**
     * Export bonus data
     */
    public function export(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $query = BonusPayment::where('client_id', $client->id)
            ->with(['sale', 'fromClient', 'bonusConfiguration']);

        // Apply filters
        if ($request->filled('bonus_type')) {
            $query->where('bonus_type', $request->bonus_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bonusPayments = $query->latest()->get();
        
        $filename = 'bonus_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bonusPayments) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Date', 'Bonus Type', 'Amount', 'Status', 'Eligibility Status', 
                'From Client', 'Sale ID', 'Description'
            ]);

            // Data
            foreach ($bonusPayments as $payment) {
                fputcsv($file, [
                    $payment->created_at->format('d/m/Y H:i'),
                    $payment->bonus_type,
                    'R$ ' . number_format($payment->amount, 2, ',', '.'),
                    $payment->status,
                    $payment->eligibility_status,
                    $payment->fromClient ? $payment->fromClient->name : 'N/A',
                    $payment->sale ? $payment->sale->id : 'N/A',
                    $payment->description ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get bonus statistics for overview
     */
    private function getBonusStatistics($client)
    {
        return [
            'total_earned' => BonusPayment::where('client_id', $client->id)
                ->where('eligibility_status', 'eligible')
                ->sum('amount'),
            'total_pending' => BonusPayment::where('client_id', $client->id)
                ->where('status', 'pending')
                ->where('eligibility_status', 'eligible')
                ->sum('amount'),
            'total_approved' => BonusPayment::where('client_id', $client->id)
                ->where('status', 'approved')
                ->where('eligibility_status', 'eligible')
                ->sum('amount'),
            'total_paid' => BonusPayment::where('client_id', $client->id)
                ->where('status', 'paid')
                ->where('eligibility_status', 'eligible')
                ->sum('amount'),
            'this_month' => BonusPayment::where('client_id', $client->id)
                ->where('eligibility_status', 'eligible')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];
    }

    /**
     * Get detailed bonus statistics
     */
    private function getDetailedBonusStatistics($client)
    {
        $baseQuery = BonusPayment::where('client_id', $client->id)
            ->where('eligibility_status', 'eligible');

        return [
            'total_earned' => $baseQuery->sum('amount'),
            'total_pending' => $baseQuery->where('status', 'pending')->sum('amount'),
            'total_approved' => $baseQuery->where('status', 'approved')->sum('amount'),
            'total_paid' => $baseQuery->where('status', 'paid')->sum('amount'),
            'this_month' => $baseQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->sum('amount'),
            'last_month' => $baseQuery->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)->sum('amount'),
            'this_year' => $baseQuery->whereYear('created_at', now()->year)->sum('amount'),
            'last_year' => $baseQuery->whereYear('created_at', now()->subYear()->year)->sum('amount'),
            'average_monthly' => $baseQuery->whereYear('created_at', now()->year)
                ->selectRaw('AVG(amount) as avg_amount')
                ->groupByRaw('MONTH(created_at)')
                ->get()->avg('avg_amount') ?? 0,
            'total_bonuses' => $baseQuery->count(),
            'conversion_rate' => $this->calculateConversionRate($client),
        ];
    }

    /**
     * Get bonus chart data
     */
    private function getBonusChartData($client, $request)
    {
        $period = $request->get('period', '12_months');
        
        $query = BonusPayment::where('client_id', $client->id)
            ->where('eligibility_status', 'eligible');

        switch ($period) {
            case '6_months':
                $query->where('created_at', '>=', now()->subMonths(6));
                break;
            case '12_months':
                $query->where('created_at', '>=', now()->subYear());
                break;
            case '24_months':
                $query->where('created_at', '>=', now()->subYears(2));
                break;
        }

        $data = $query->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $data->pluck('month')->map(function($month) {
                return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y');
            })->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }

    /**
     * Get bonus type breakdown
     */
    private function getBonusTypeBreakdown($client)
    {
        return BonusPayment::where('client_id', $client->id)
            ->where('eligibility_status', 'eligible')
            ->selectRaw('bonus_type, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('bonus_type')
            ->get()
            ->map(function($item) {
                return [
                    'type' => $item->bonus_type,
                    'total' => $item->total,
                    'count' => $item->count,
                    'percentage' => 0, // Will be calculated in the view
                ];
            });
    }

    /**
     * Get monthly earnings for the last 12 months
     */
    private function getMonthlyEarnings($client)
    {
        $months = collect();
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $total = BonusPayment::where('client_id', $client->id)
                ->where('eligibility_status', 'eligible')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
                
            $months->push([
                'month' => $date->format('M Y'),
                'total' => $total,
                'date' => $date->format('Y-m'),
            ]);
        }
        
        return $months;
    }

    /**
     * Calculate conversion rate
     */
    private function calculateConversionRate($client)
    {
        $totalLeads = $client->leads()->count();
        $convertedLeads = $client->leads()->converted()->count();
        
        if ($totalLeads == 0) {
            return 0;
        }
        
        return round(($convertedLeads / $totalLeads) * 100, 2);
    }
}