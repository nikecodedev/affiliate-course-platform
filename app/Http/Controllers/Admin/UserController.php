<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by user type
        if ($request->filled('type')) {
            if ($request->type === 'affiliates') {
                $query->affiliates();
            } elseif ($request->type === 'customers') {
                $query->where('is_affiliate', false);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['sales', 'affiliateSales', 'referrals', 'commissionPayments', 'withdrawals']);
        
        $stats = [
            'total_sales' => $user->affiliateSales()->confirmed()->count(),
            'total_revenue' => $user->affiliateSales()->confirmed()->sum('amount'),
            'total_commission_earned' => $user->total_commission_earned,
            'total_commission_paid' => $user->total_commission_paid,
            'pending_commission' => $user->pending_commission,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->fill($request->except(['password']));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Check if user has sales
        if ($user->sales()->exists() || $user->affiliateSales()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete user with sales history.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle affiliate status
     */
    public function toggleAffiliate(User $user)
    {
        $user->is_affiliate = !$user->is_affiliate;
        
        // Generate affiliate code if becoming affiliate
        if ($user->is_affiliate && !$user->affiliate_code) {
            $user->affiliate_code = $user->generateAffiliateCode();
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'is_affiliate' => $user->is_affiliate,
        ]);
    }

    /**
     * Generate new affiliate code
     */
    public function generateAffiliateCode(User $user)
    {
        $user->affiliate_code = $user->generateAffiliateCode();
        $user->save();

        return redirect()->back()
            ->with('success', 'New affiliate code generated: ' . $user->affiliate_code);
    }

    /**
     * Get user statistics
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $stats = [
            'total_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_affiliates' => User::affiliates()->whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_users' => User::active()->whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_affiliates' => User::affiliates()->whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get top affiliates
     */
    public function getTopAffiliates(Request $request)
    {
        $limit = $request->get('limit', 10);
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $topAffiliates = User::affiliates()
            ->selectRaw('users.*, COUNT(sales.id) as sales_count, SUM(sales.amount) as total_sales, SUM(sales.commission_amount) as total_commission')
            ->leftJoin('sales', 'users.id', '=', 'sales.affiliate_id')
            ->where('sales.status', 'confirmed')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->groupBy('users.id')
            ->orderBy('total_commission', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($topAffiliates);
    }

    /**
     * Get start date based on period
     */
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->subWeek();
            case 'month':
                return now()->subMonth();
            case 'quarter':
                return now()->subQuarter();
            case 'year':
                return now()->subYear();
            default:
                return now()->subMonth();
        }
    }
}

