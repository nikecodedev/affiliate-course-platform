<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralNetwork;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferralNetworkController extends Controller
{
    /**
     * Display network overview
     */
    public function index(Request $request)
    {
        $query = ReferralNetwork::with(['client', 'sponsor', 'parent']);

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by sponsor
        if ($request->filled('sponsor_id')) {
            $query->where('sponsor_id', $request->sponsor_id);
        }

        // Filter by active status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $networkPositions = $query->orderBy('level')->orderBy('created_at')->paginate(50);
        
        $statistics = $this->getNetworkStatistics();
        $sponsors = Client::has('sponsoredClients')->get();

        return view('admin.referral-networks.index', compact('networkPositions', 'statistics', 'sponsors'));
    }

    /**
     * Show network visualization
     */
    public function visualization(Request $request)
    {
        $rootClientId = $request->get('root_client_id');
        $maxDepth = min($request->get('max_depth', 5), 10); // Limit to 10 levels for performance

        if ($rootClientId) {
            $rootPosition = ReferralNetwork::where('client_id', $rootClientId)->first();
        } else {
            // Get the first root position (level 1)
            $rootPosition = ReferralNetwork::where('level', 1)->first();
        }

        if (!$rootPosition) {
            return view('admin.referral-networks.visualization', [
                'networkData' => null,
                'rootClientId' => $rootClientId,
                'maxDepth' => $maxDepth
            ]);
        }

        $networkData = $this->buildNetworkTree($rootPosition, $maxDepth);
        
        return view('admin.referral-networks.visualization', compact('networkData', 'rootClientId', 'maxDepth'));
    }

    /**
     * Show specific client network details
     */
    public function show(Client $client)
    {
        $networkPosition = ReferralNetwork::where('client_id', $client->id)->first();
        
        if (!$networkPosition) {
            return redirect()->back()
                ->with('error', 'Client is not in the referral network.');
        }

        $networkPosition->load(['client', 'sponsor', 'parent']);
        
        // Get network statistics for this client
        $networkStats = $networkPosition->getNetworkStats();
        
        // Get descendants (downline)
        $descendants = $this->getClientDescendants($networkPosition);
        
        // Get ancestors (upline)
        $ancestors = $this->getClientAncestors($networkPosition);
        
        // Get recent activity
        $recentActivity = $this->getClientNetworkActivity($client);

        return view('admin.referral-networks.show', compact(
            'networkPosition', 
            'networkStats', 
            'descendants', 
            'ancestors', 
            'recentActivity'
        ));
    }

    /**
     * Get network statistics
     */
    public function statistics()
    {
        $statistics = $this->getNetworkStatistics();
        
        // Additional detailed statistics
        $detailedStats = [
            'total_clients' => ReferralNetwork::count(),
            'active_clients' => ReferralNetwork::active()->count(),
            'clients_by_level' => ReferralNetwork::selectRaw('level, COUNT(*) as count')
                ->groupBy('level')
                ->orderBy('level')
                ->get(),
            'total_volume' => ReferralNetwork::sum(DB::raw('left_volume + right_volume')),
            'average_volume_per_client' => ReferralNetwork::selectRaw('AVG(left_volume + right_volume) as avg_volume')->value('avg_volume'),
            'top_performers' => ReferralNetwork::selectRaw('client_id, (left_volume + right_volume) as total_volume')
                ->with('client')
                ->orderBy('total_volume', 'desc')
                ->limit(10)
                ->get(),
            'balanced_networks' => ReferralNetwork::whereRaw('left_count > 0 AND right_count > 0')->count(),
            'unbalanced_networks' => ReferralNetwork::whereRaw('left_count = 0 OR right_count = 0')->count(),
        ];

        return view('admin.referral-networks.statistics', compact('statistics', 'detailedStats'));
    }

    /**
     * Build network tree for visualization
     */
    private function buildNetworkTree($rootPosition, $maxDepth, $currentDepth = 0)
    {
        if ($currentDepth >= $maxDepth) {
            return null;
        }

        $node = [
            'id' => $rootPosition->client_id,
            'name' => $rootPosition->client->name,
            'email' => $rootPosition->client->email,
            'level' => $rootPosition->level,
            'position' => $rootPosition->position,
            'left_count' => $rootPosition->left_count,
            'right_count' => $rootPosition->right_count,
            'left_volume' => $rootPosition->left_volume,
            'right_volume' => $rootPosition->right_volume,
            'total_volume' => $rootPosition->left_volume + $rootPosition->right_volume,
            'is_active' => $rootPosition->is_active,
            'children' => []
        ];

        // Get children
        $children = ReferralNetwork::where('parent_id', $rootPosition->id)
            ->orderBy('position')
            ->get();

        foreach ($children as $child) {
            $childNode = $this->buildNetworkTree($child, $maxDepth, $currentDepth + 1);
            if ($childNode) {
                $node['children'][] = $childNode;
            }
        }

        return $node;
    }

    /**
     * Get client descendants
     */
    private function getClientDescendants($networkPosition)
    {
        return ReferralNetwork::where('sponsor_id', $networkPosition->client_id)
            ->with(['client', 'sponsor'])
            ->orderBy('level')
            ->orderBy('created_at')
            ->get()
            ->groupBy('level');
    }

    /**
     * Get client ancestors
     */
    private function getClientAncestors($networkPosition)
    {
        $ancestors = [];
        $current = $networkPosition;

        while ($current->parent_id) {
            $parent = ReferralNetwork::with('client')->find($current->parent_id);
            if ($parent) {
                $ancestors[] = $parent;
                $current = $parent;
            } else {
                break;
            }
        }

        return collect($ancestors)->reverse()->values();
    }

    /**
     * Get client network activity
     */
    private function getClientNetworkActivity($client)
    {
        // Get recent sales by this client
        $recentSales = $client->sales()
            ->with('plan')
            ->latest()
            ->limit(10)
            ->get();

        // Get recent network additions (sponsored clients)
        $recentSponsorships = ReferralNetwork::where('sponsor_id', $client->id)
            ->with('client')
            ->latest()
            ->limit(10)
            ->get();

        return [
            'recent_sales' => $recentSales,
            'recent_sponsorships' => $recentSponsorships,
        ];
    }

    /**
     * Get network statistics
     */
    private function getNetworkStatistics()
    {
        return [
            'total_positions' => ReferralNetwork::count(),
            'active_positions' => ReferralNetwork::active()->count(),
            'total_volume' => ReferralNetwork::sum(DB::raw('left_volume + right_volume')),
            'total_left_volume' => ReferralNetwork::sum('left_volume'),
            'total_right_volume' => ReferralNetwork::sum('right_volume'),
            'total_sponsorships' => ReferralNetwork::sum(DB::raw('left_count + right_count')),
            'max_level' => ReferralNetwork::max('level'),
            'average_level' => ReferralNetwork::avg('level'),
            'balanced_networks' => ReferralNetwork::whereRaw('left_count > 0 AND right_count > 0')->count(),
            'unbalanced_networks' => ReferralNetwork::whereRaw('left_count = 0 OR right_count = 0')->count(),
        ];
    }

    /**
     * Export network data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $query = ReferralNetwork::with(['client', 'sponsor', 'parent']);

        // Apply filters
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('sponsor_id')) {
            $query->where('sponsor_id', $request->sponsor_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $data = $query->get()->map(function ($position) {
            return [
                'Client ID' => $position->client_id,
                'Client Name' => $position->client->name,
                'Client Email' => $position->client->email,
                'Sponsor ID' => $position->sponsor_id,
                'Sponsor Name' => $position->sponsor->name ?? 'N/A',
                'Parent ID' => $position->parent_id,
                'Position' => $position->position,
                'Level' => $position->level,
                'Left Count' => $position->left_count,
                'Right Count' => $position->right_count,
                'Left Volume' => $position->left_volume,
                'Right Volume' => $position->right_volume,
                'Total Volume' => $position->left_volume + $position->right_volume,
                'Is Active' => $position->is_active ? 'Yes' : 'No',
                'Activated At' => $position->activated_at?->format('Y-m-d H:i:s'),
                'Created At' => $position->created_at->format('Y-m-d H:i:s'),
            ];
        });

        if ($format === 'csv') {
            $filename = 'referral_network_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Write headers
                if ($data->isNotEmpty()) {
                    fputcsv($file, array_keys($data->first()));
                }
                
                // Write data
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Unsupported export format.');
    }

    /**
     * Search clients in network
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $clients = ReferralNetwork::with('client')
            ->whereHas('client', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($position) {
                return [
                    'id' => $position->client_id,
                    'name' => $position->client->name,
                    'email' => $position->client->email,
                    'level' => $position->level,
                    'position' => $position->position,
                ];
            });

        return response()->json($clients);
    }
}