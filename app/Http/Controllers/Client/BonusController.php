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
}