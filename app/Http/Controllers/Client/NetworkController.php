<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ReferralNetwork;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{
    /**
     * Display client's network overview
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        
        // Get client's network position
        $networkPosition = ReferralNetwork::where('client_id', $client->id)->first();
        
        if (!$networkPosition) {
            return view('client.network.index', [
                'inNetwork' => false,
                'networkPosition' => null,
                'statistics' => null,
                'sponsoredClients' => collect(),
            ]);
        }

        $networkPosition->load(['client', 'sponsor', 'parent']);
        
        // Get network statistics
        $statistics = $networkPosition->getNetworkStats();
        
        // Get sponsored clients
        $sponsoredClients = $this->getSponsoredClients($networkPosition);
        
        return view('client.network.index', compact(
            'networkPosition',
            'statistics',
            'sponsoredClients'
        ));
    }

    /**
     * Display network visualization
     */
    public function visualization()
    {
        $client = Auth::guard('client')->user();
        
        // Get client's network position
        $networkPosition = ReferralNetwork::where('client_id', $client->id)->first();
        
        if (!$networkPosition) {
            return view('client.network.visualization', [
                'inNetwork' => false,
                'networkData' => null,
            ]);
        }

        // Build network tree starting from this client
        $networkData = $this->buildNetworkTree($networkPosition, 3); // Show 3 levels deep
        
        return view('client.network.visualization', compact('networkData'));
    }

    /**
     * Get sponsored clients
     */
    private function getSponsoredClients($networkPosition)
    {
        return ReferralNetwork::where('sponsor_id', $networkPosition->client_id)
            ->with(['client'])
            ->orderBy('created_at')
            ->get()
            ->groupBy('position');
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
     * Get network statistics for charts
     */
    public function getChartData(Request $request)
    {
        $client = Auth::guard('client')->user();
        $networkPosition = ReferralNetwork::where('client_id', $client->id)->first();
        
        if (!$networkPosition) {
            return response()->json([]);
        }

        $type = $request->get('type', 'volume');
        
        switch ($type) {
            case 'volume':
                return response()->json([
                    'labels' => ['Left Leg', 'Right Leg'],
                    'data' => [$networkPosition->left_volume, $networkPosition->right_volume],
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                ]);
            case 'count':
                return response()->json([
                    'labels' => ['Left Leg', 'Right Leg'],
                    'data' => [$networkPosition->left_count, $networkPosition->right_count],
                    'backgroundColor' => ['#4BC0C0', '#FF9F40'],
                ]);
            default:
                return response()->json([]);
        }
    }
}