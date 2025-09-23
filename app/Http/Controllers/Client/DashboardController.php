<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientInvoice;
use App\Models\ClientLead;
use App\Models\ClientTransaction;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show client dashboard
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        
        // Get active invoice
        $activeInvoice = $client->activeInvoice();
        
        // Get dashboard statistics
        $stats = [
            'total_leads' => $client->leads()->count(),
            'active_leads' => $client->leads()->active()->count(),
            'total_earnings' => $client->total_earnings,
            'available_balance' => $client->available_balance,
            'open_tickets' => $client->supportTickets()->open()->count(),
            'course_progress' => $client->courseProgress()->completed()->count(),
        ];

        // Get recent transactions
        $recentTransactions = $client->transactions()
            ->latest()
            ->limit(5)
            ->get();

        // Get recent leads
        $recentLeads = $client->leads()
            ->latest()
            ->limit(5)
            ->get();

        // Get recent support tickets
        $recentTickets = $client->supportTickets()
            ->latest()
            ->limit(3)
            ->get();

        // Get course progress if client has course access
        $courseProgress = null;
        if ($client->hasCourseAccess()) {
            $courseProgress = $client->courseProgress()
                ->with(['course', 'lesson'])
                ->latest()
                ->limit(3)
                ->get();
        }

        // Get monthly earnings chart data
        $monthlyEarnings = $this->getMonthlyEarningsData($client);

        return view('client.dashboard', compact(
            'client',
            'activeInvoice',
            'stats',
            'recentTransactions',
            'recentLeads',
            'recentTickets',
            'courseProgress',
            'monthlyEarnings'
        ));
    }

    /**
     * Get monthly earnings data for chart
     */
    private function getMonthlyEarningsData($client)
    {
        $months = [];
        $earnings = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M/Y');
            
            $monthlyEarnings = $client->transactions()
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $months[] = $month;
            $earnings[] = $monthlyEarnings;
        }

        return [
            'months' => $months,
            'earnings' => $earnings,
        ];
    }

    /**
     * Get capture sites for client
     */
    public function captureSites()
    {
        $client = Auth::guard('client')->user();
        
        // Get client's custom capture sites
        $captureSites = [
            [
                'name' => 'Site Principal',
                'url' => route('client.capture.main', ['code' => $client->id]),
                'description' => 'Site de captura principal com seus tracking tags',
                'status' => 'active',
            ],
            [
                'name' => 'Site Secundário',
                'url' => route('client.capture.secondary', ['code' => $client->id]),
                'description' => 'Site de captura secundário',
                'status' => 'active',
            ],
        ];

        return view('client.capture-sites', compact('captureSites'));
    }

    /**
     * Show capture site preview
     */
    public function showCaptureSite($type)
    {
        $client = Auth::guard('client')->user();
        
        return view('client.capture-site-preview', compact('client', 'type'));
    }

    /**
     * Get quick stats for dashboard widgets
     */
    public function getQuickStats()
    {
        $client = Auth::guard('client')->user();
        
        return response()->json([
            'total_leads' => $client->leads()->count(),
            'active_leads' => $client->leads()->active()->count(),
            'total_earnings' => number_format($client->total_earnings, 2, ',', '.'),
            'available_balance' => number_format($client->available_balance, 2, ',', '.'),
        ]);
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity()
    {
        $client = Auth::guard('client')->user();
        
        $activities = collect();
        
        // Add recent transactions
        $client->transactions()
            ->latest()
            ->limit(3)
            ->get()
            ->each(function ($transaction) use ($activities) {
                $activities->push([
                    'type' => 'transaction',
                    'title' => $transaction->description,
                    'amount' => $transaction->formatted_amount,
                    'status' => $transaction->status_badge_text,
                    'created_at' => $transaction->created_at,
                    'icon' => $transaction->type === 'credit' ? 'plus-circle' : 'minus-circle',
                    'color' => $transaction->type === 'credit' ? 'success' : 'danger',
                ]);
            });
        
        // Add recent leads
        $client->leads()
            ->latest()
            ->limit(2)
            ->get()
            ->each(function ($lead) use ($activities) {
                $activities->push([
                    'type' => 'lead',
                    'title' => "Novo lead: {$lead->name}",
                    'subtitle' => $lead->email,
                    'status' => $lead->status_badge_text,
                    'created_at' => $lead->created_at,
                    'icon' => 'person-plus',
                    'color' => 'info',
                ]);
            });
        
        // Sort by created_at desc
        $activities = $activities->sortByDesc('created_at')->take(5);
        
        return response()->json($activities->values());
    }
}
