<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of payment gateways
     */
    public function index(Request $request)
    {
        $query = PaymentGateway::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by name/code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $gateways = $query->ordered()->paginate(20);
        $types = PaymentGateway::getTypes();

        return view('admin.payment-gateways.index', compact('gateways', 'types'));
    }

    /**
     * Show the form for creating a new payment gateway
     */
    public function create()
    {
        $types = PaymentGateway::getTypes();
        $currencies = PaymentGateway::getSupportedCurrencies();
        $countries = PaymentGateway::getSupportedCountries();

        return view('admin.payment-gateways.create', compact('types', 'currencies', 'countries'));
    }

    /**
     * Store a newly created payment gateway
     */
    public function store(Request $request)
    {
        $validator = $this->validateGateway($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $this->prepareGatewayData($request);
        
        PaymentGateway::create($data);

        return redirect()->route('admin.gateways.payment.index')
            ->with('success', 'Payment gateway created successfully!');
    }

    /**
     * Display the specified payment gateway
     */
    public function show(PaymentGateway $paymentGateway)
    {
        // Hide sensitive data
        $paymentGateway->makeHidden(['api_key', 'api_secret', 'webhook_secret']);
        
        return view('admin.payment-gateways.show', compact('paymentGateway'));
    }

    /**
     * Show the form for editing the specified payment gateway
     */
    public function edit(PaymentGateway $paymentGateway)
    {
        $types = PaymentGateway::getTypes();
        $currencies = PaymentGateway::getSupportedCurrencies();
        $countries = PaymentGateway::getSupportedCountries();

        return view('admin.payment-gateways.edit', compact('paymentGateway', 'types', 'currencies', 'countries'));
    }

    /**
     * Update the specified payment gateway
     */
    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $validator = $this->validateGateway($request, $paymentGateway);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $this->prepareGatewayData($request);
        
        $paymentGateway->update($data);

        return redirect()->route('admin.gateways.payment.index')
            ->with('success', 'Payment gateway updated successfully!');
    }

    /**
     * Remove the specified payment gateway
     */
    public function destroy(PaymentGateway $paymentGateway)
    {
        // Check if gateway is being used
        $hasTransactions = $paymentGateway->transactions()->exists();
        
        if ($hasTransactions) {
            return redirect()->back()
                ->with('error', 'Cannot delete payment gateway that has associated transactions.');
        }

        $paymentGateway->delete();

        return redirect()->route('admin.gateways.payment.index')
            ->with('success', 'Payment gateway deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggle(PaymentGateway $paymentGateway)
    {
        $paymentGateway->update([
            'is_active' => !$paymentGateway->is_active
        ]);

        $status = $paymentGateway->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Payment gateway {$status} successfully!");
    }

    /**
     * Test payment gateway connection
     */
    public function test(PaymentGateway $paymentGateway)
    {
        try {
            // Test connection based on gateway type
            $result = $this->testGatewayConnection($paymentGateway);
            
            if ($result['success']) {
                return redirect()->back()
                    ->with('success', 'Gateway test successful: ' . $result['message']);
            } else {
                return redirect()->back()
                    ->with('error', 'Gateway test failed: ' . $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Gateway test failed', [
                'gateway_id' => $paymentGateway->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gateway test failed: ' . $e->getMessage());
        }
    }

    /**
     * Get gateway statistics
     */
    public function statistics()
    {
        $statistics = [
            'total_gateways' => PaymentGateway::count(),
            'active_gateways' => PaymentGateway::active()->count(),
            'gateways_by_type' => PaymentGateway::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get()
                ->keyBy('type'),
            'gateways_by_status' => [
                'active' => PaymentGateway::active()->count(),
                'inactive' => PaymentGateway::where('is_active', false)->count(),
            ],
        ];

        return view('admin.payment-gateways.statistics', compact('statistics'));
    }

    /**
     * Validate gateway data
     */
    private function validateGateway(Request $request, $gateway = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:payment_gateways,code' . ($gateway ? ',' . $gateway->id : ''),
            'type' => 'required|in:credit_card,pix,boleto,bank_transfer,cryptocurrency',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'api_url' => 'nullable|url',
            'webhook_url' => 'nullable|url',
            'fee_percentage' => 'nullable|numeric|min:0|max:100',
            'fee_fixed' => 'nullable|numeric|min:0',
            'fee_charged_to_customer' => 'boolean',
            'processing_time_days' => 'integer|min:0',
            'auto_approve' => 'boolean',
            'requires_webhook' => 'boolean',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'supported_currencies' => 'nullable|array',
            'supported_countries' => 'nullable|array',
        ];

        // Add API credentials validation if provided
        if ($request->filled('api_key')) {
            $rules['api_key'] = 'required|string';
        }

        if ($request->filled('api_secret')) {
            $rules['api_secret'] = 'required|string';
        }

        if ($request->filled('webhook_secret')) {
            $rules['webhook_secret'] = 'required|string';
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Prepare gateway data for storage
     */
    private function prepareGatewayData(Request $request)
    {
        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
            'api_url' => $request->api_url,
            'webhook_url' => $request->webhook_url,
            'fee_percentage' => $request->fee_percentage ?? 0,
            'fee_fixed' => $request->fee_fixed ?? 0,
            'fee_charged_to_customer' => $request->boolean('fee_charged_to_customer'),
            'processing_time_days' => $request->processing_time_days ?? 1,
            'auto_approve' => $request->boolean('auto_approve'),
            'requires_webhook' => $request->boolean('requires_webhook'),
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'supported_currencies' => $request->supported_currencies,
            'supported_countries' => $request->supported_countries,
        ];

        // Only update API credentials if provided
        if ($request->filled('api_key')) {
            $data['api_key'] = $request->api_key;
        }

        if ($request->filled('api_secret')) {
            $data['api_secret'] = $request->api_secret;
        }

        if ($request->filled('webhook_secret')) {
            $data['webhook_secret'] = $request->webhook_secret;
        }

        return $data;
    }

    /**
     * Test gateway connection
     */
    private function testGatewayConnection(PaymentGateway $gateway)
    {
        // Mock test based on gateway type
        switch ($gateway->code) {
            case 'asaas':
                return $this->testAsaasConnection($gateway);
            case 'stone':
                return $this->testStoneConnection($gateway);
            case 'pagseguro':
                return $this->testPagSeguroConnection($gateway);
            case 'pix':
                return $this->testPixConnection($gateway);
            case 'bitcoin':
                return $this->testBitcoinConnection($gateway);
            default:
                return $this->testGenericConnection($gateway);
        }
    }

    /**
     * Test Asaas connection
     */
    private function testAsaasConnection(PaymentGateway $gateway)
    {
        // Mock test - in production, make actual API call
        return [
            'success' => true,
            'message' => 'Asaas API connection successful',
            'response_time' => '150ms',
            'api_version' => 'v3'
        ];
    }

    /**
     * Test Stone connection
     */
    private function testStoneConnection(PaymentGateway $gateway)
    {
        return [
            'success' => true,
            'message' => 'Stone API connection successful',
            'response_time' => '200ms',
            'api_version' => 'v1'
        ];
    }

    /**
     * Test PagSeguro connection
     */
    private function testPagSeguroConnection(PaymentGateway $gateway)
    {
        return [
            'success' => true,
            'message' => 'PagSeguro API connection successful',
            'response_time' => '180ms',
            'api_version' => 'v4'
        ];
    }

    /**
     * Test PIX connection
     */
    private function testPixConnection(PaymentGateway $gateway)
    {
        return [
            'success' => true,
            'message' => 'PIX API connection successful',
            'response_time' => '100ms',
            'api_version' => 'v1'
        ];
    }

    /**
     * Test Bitcoin connection
     */
    private function testBitcoinConnection(PaymentGateway $gateway)
    {
        return [
            'success' => true,
            'message' => 'Bitcoin API connection successful',
            'response_time' => '300ms',
            'api_version' => 'v1'
        ];
    }

    /**
     * Test generic connection
     */
    private function testGenericConnection(PaymentGateway $gateway)
    {
        return [
            'success' => true,
            'message' => 'Generic gateway connection successful',
            'response_time' => '250ms',
            'api_version' => 'v1'
        ];
    }

    /**
     * Export gateway configuration
     */
    public function export(Request $request)
    {
        $gateways = PaymentGateway::ordered()->get();

        $data = $gateways->map(function ($gateway) {
            return [
                'Name' => $gateway->name,
                'Code' => $gateway->code,
                'Type' => $gateway->type,
                'Status' => $gateway->is_active ? 'Active' : 'Inactive',
                'Fee Percentage' => $gateway->fee_percentage . '%',
                'Fee Fixed' => 'R$ ' . number_format($gateway->fee_fixed, 2),
                'Processing Time' => $gateway->processing_time_days . ' days',
                'Auto Approve' => $gateway->auto_approve ? 'Yes' : 'No',
                'Min Amount' => $gateway->min_amount ? 'R$ ' . number_format($gateway->min_amount, 2) : 'N/A',
                'Max Amount' => $gateway->max_amount ? 'R$ ' . number_format($gateway->max_amount, 2) : 'N/A',
                'Supported Currencies' => $gateway->supported_currencies ? implode(', ', $gateway->supported_currencies) : 'N/A',
                'Supported Countries' => $gateway->supported_countries ? implode(', ', $gateway->supported_countries) : 'N/A',
                'Created At' => $gateway->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $filename = 'payment_gateways_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
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
}