<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentGateway;
use App\Models\Sale;
use App\Models\ClientInvoice;
use App\Services\BonusCalculationService;

class WebhookController extends Controller
{
    /**
     * Handle Asaas webhook
     */
    public function asaas(Request $request)
    {
        $gateway = PaymentGateway::where('code', 'asaas')->first();
        
        if (!$gateway || !$gateway->is_active) {
            return response()->json(['error' => 'Gateway not found or inactive'], 404);
        }

        try {
            $payload = $request->all();
            Log::info('Asaas webhook received', ['payload' => $payload]);
            
            $result = $this->processPayment($payload, 'asaas');
            return response()->json(['success' => true, 'processed' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Asaas webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle Stone webhook
     */
    public function stone(Request $request)
    {
        $gateway = PaymentGateway::where('code', 'stone')->first();
        
        if (!$gateway || !$gateway->is_active) {
            return response()->json(['error' => 'Gateway not found or inactive'], 404);
        }

        try {
            $payload = $request->all();
            Log::info('Stone webhook received', ['payload' => $payload]);
            
            $result = $this->processPayment($payload, 'stone');
            return response()->json(['success' => true, 'processed' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Stone webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle PagSeguro webhook
     */
    public function pagseguro(Request $request)
    {
        $gateway = PaymentGateway::where('code', 'pagseguro')->first();
        
        if (!$gateway || !$gateway->is_active) {
            return response()->json(['error' => 'Gateway not found or inactive'], 404);
        }

        try {
            $payload = $request->all();
            Log::info('PagSeguro webhook received', ['payload' => $payload]);
            
            $result = $this->processPayment($payload, 'pagseguro');
            return response()->json(['success' => true, 'processed' => $result]);
            
        } catch (\Exception $e) {
            Log::error('PagSeguro webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle PIX webhook
     */
    public function pix(Request $request)
    {
        $gateway = PaymentGateway::where('code', 'pix')->first();
        
        if (!$gateway || !$gateway->is_active) {
            return response()->json(['error' => 'Gateway not found or inactive'], 404);
        }

        try {
            $payload = $request->all();
            Log::info('PIX webhook received', ['payload' => $payload]);
            
            $result = $this->processPayment($payload, 'pix');
            return response()->json(['success' => true, 'processed' => $result]);
            
        } catch (\Exception $e) {
            Log::error('PIX webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle Bitcoin webhook
     */
    public function bitcoin(Request $request)
    {
        $gateway = PaymentGateway::where('code', 'bitcoin')->first();
        
        if (!$gateway || !$gateway->is_active) {
            return response()->json(['error' => 'Gateway not found or inactive'], 404);
        }

        try {
            $payload = $request->all();
            Log::info('Bitcoin webhook received', ['payload' => $payload]);
            
            $result = $this->processPayment($payload, 'bitcoin');
            return response()->json(['success' => true, 'processed' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Bitcoin webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Process payment webhook
     */
    private function processPayment(array $payload, string $gatewayType)
    {
        // Extract payment reference based on gateway type
        $paymentReference = $this->extractPaymentReference($payload, $gatewayType);
        $status = $this->extractStatus($payload, $gatewayType);
        $amount = $this->extractAmount($payload, $gatewayType);

        if (!$paymentReference || !$status) {
            throw new \Exception('Invalid webhook payload');
        }

        // Find related sale or invoice
        $sale = Sale::where('payment_reference', $paymentReference)->first();
        $invoice = ClientInvoice::where('payment_reference', $paymentReference)->first();

        if ($sale) {
            return $this->updateSaleStatus($sale, $status, $amount);
        } elseif ($invoice) {
            return $this->updateInvoiceStatus($invoice, $status, $amount);
        }

        Log::warning('Payment not found in system', [
            'gateway' => $gatewayType,
            'reference' => $paymentReference
        ]);
        
        return false;
    }

    /**
     * Extract payment reference from payload
     */
    private function extractPaymentReference(array $payload, string $gatewayType)
    {
        switch ($gatewayType) {
            case 'asaas':
                return $payload['id'] ?? null;
            case 'stone':
                return $payload['transaction_id'] ?? null;
            case 'pagseguro':
                return $payload['transaction_code'] ?? null;
            case 'pix':
                return $payload['pix_id'] ?? null;
            case 'bitcoin':
                return $payload['transaction_hash'] ?? null;
            default:
                return $payload['id'] ?? $payload['transaction_id'] ?? null;
        }
    }

    /**
     * Extract status from payload
     */
    private function extractStatus(array $payload, string $gatewayType)
    {
        return $payload['status'] ?? $payload['state'] ?? null;
    }

    /**
     * Extract amount from payload
     */
    private function extractAmount(array $payload, string $gatewayType)
    {
        return $payload['amount'] ?? $payload['value'] ?? $payload['total'] ?? null;
    }

    /**
     * Update sale status
     */
    private function updateSaleStatus(Sale $sale, string $status, $amount = null)
    {
        $mappedStatus = $this->mapGatewayStatusToSaleStatus($status);
        
        if ($mappedStatus && $sale->status !== $mappedStatus) {
            $sale->update([
                'status' => $mappedStatus,
                'amount' => $amount ?: $sale->amount,
            ]);

            // If sale is confirmed, calculate bonuses
            if ($mappedStatus === 'confirmed') {
                $bonusService = new BonusCalculationService();
                $bonusService->calculateBonusesForSale($sale);
            }

            Log::info('Sale status updated via webhook', [
                'sale_id' => $sale->id,
                'new_status' => $mappedStatus
            ]);

            return true;
        }

        return false;
    }

    /**
     * Update invoice status
     */
    private function updateInvoiceStatus(ClientInvoice $invoice, string $status, $amount = null)
    {
        $mappedStatus = $this->mapGatewayStatusToInvoiceStatus($status);
        
        if ($mappedStatus && $invoice->status !== $mappedStatus) {
            $invoice->update([
                'status' => $mappedStatus,
                'amount' => $amount ?: $invoice->amount,
                'paid_at' => $mappedStatus === 'paid' ? now() : null,
            ]);

            Log::info('Invoice status updated via webhook', [
                'invoice_id' => $invoice->id,
                'new_status' => $mappedStatus
            ]);

            return true;
        }

        return false;
    }

    /**
     * Map gateway status to sale status
     */
    private function mapGatewayStatusToSaleStatus(string $gatewayStatus)
    {
        $mapping = [
            'CONFIRMED' => 'confirmed',
            'PAID' => 'confirmed',
            'COMPLETED' => 'confirmed',
            'APPROVED' => 'confirmed',
            'PENDING' => 'pending',
            'WAITING' => 'pending',
            'CANCELLED' => 'cancelled',
            'CANCELED' => 'cancelled',
            'FAILED' => 'cancelled',
            'REJECTED' => 'cancelled',
            'REFUNDED' => 'refunded',
        ];

        return $mapping[strtoupper($gatewayStatus)] ?? null;
    }

    /**
     * Map gateway status to invoice status
     */
    private function mapGatewayStatusToInvoiceStatus(string $gatewayStatus)
    {
        $mapping = [
            'CONFIRMED' => 'paid',
            'PAID' => 'paid',
            'COMPLETED' => 'paid',
            'APPROVED' => 'paid',
            'PENDING' => 'pending',
            'WAITING' => 'pending',
            'CANCELLED' => 'cancelled',
            'CANCELED' => 'cancelled',
            'FAILED' => 'cancelled',
            'REJECTED' => 'cancelled',
            'REFUNDED' => 'refunded',
        ];

        return $mapping[strtoupper($gatewayStatus)] ?? null;
    }
}