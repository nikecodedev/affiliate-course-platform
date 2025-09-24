<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_active',
        'sort_order',
        'api_key',
        'api_secret',
        'webhook_secret',
        'api_url',
        'webhook_url',
        'fee_percentage',
        'fee_fixed',
        'fee_charged_to_customer',
        'supported_currencies',
        'supported_countries',
        'processing_time_days',
        'auto_approve',
        'requires_webhook',
        'min_amount',
        'max_amount',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
        'fee_charged_to_customer' => 'boolean',
        'supported_currencies' => 'array',
        'supported_countries' => 'array',
        'auto_approve' => 'boolean',
        'requires_webhook' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
        'webhook_secret',
    ];

    /**
     * Scope for active gateways
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get encrypted API key
     */
    public function getApiKeyAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Set encrypted API key
     */
    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Get encrypted API secret
     */
    public function getApiSecretAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Set encrypted API secret
     */
    public function setApiSecretAttribute($value)
    {
        $this->attributes['api_secret'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Get encrypted webhook secret
     */
    public function getWebhookSecretAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Set encrypted webhook secret
     */
    public function setWebhookSecretAttribute($value)
    {
        $this->attributes['webhook_secret'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Calculate fee for amount
     */
    public function calculateFee($amount)
    {
        $percentageFee = $amount * $this->fee_percentage / 100;
        $totalFee = $percentageFee + $this->fee_fixed;
        
        return [
            'percentage_fee' => $percentageFee,
            'fixed_fee' => $this->fee_fixed,
            'total_fee' => $totalFee,
            'net_amount' => $amount - ($this->fee_charged_to_customer ? 0 : $totalFee),
            'total_charged' => $amount + ($this->fee_charged_to_customer ? $totalFee : 0),
        ];
    }

    /**
     * Check if amount is within limits
     */
    public function isAmountValid($amount)
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }

        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }

        return true;
    }

    /**
     * Check if currency is supported
     */
    public function isCurrencySupported($currency)
    {
        if (!$this->supported_currencies) {
            return true; // No restriction
        }

        return in_array($currency, $this->supported_currencies);
    }

    /**
     * Check if country is supported
     */
    public function isCountrySupported($country)
    {
        if (!$this->supported_countries) {
            return true; // No restriction
        }

        return in_array($country, $this->supported_countries);
    }

    /**
     * Get gateway types
     */
    public static function getTypes()
    {
        return [
            'credit_card' => 'Credit Card',
            'pix' => 'PIX',
            'boleto' => 'Boleto',
            'bank_transfer' => 'Bank Transfer',
            'cryptocurrency' => 'Cryptocurrency',
        ];
    }

    /**
     * Get supported currencies
     */
    public static function getSupportedCurrencies()
    {
        return [
            'BRL' => 'Brazilian Real',
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'BTC' => 'Bitcoin',
            'ETH' => 'Ethereum',
        ];
    }

    /**
     * Get supported countries
     */
    public static function getSupportedCountries()
    {
        return [
            'BR' => 'Brazil',
            'US' => 'United States',
            'CA' => 'Canada',
            'GB' => 'United Kingdom',
            'DE' => 'Germany',
            'FR' => 'France',
            'ES' => 'Spain',
            'IT' => 'Italy',
        ];
    }

    /**
     * Process payment (placeholder for gateway-specific implementation)
     */
    public function processPayment($paymentData)
    {
        // This would be implemented differently for each gateway
        // For now, return a mock response
        
        $fee = $this->calculateFee($paymentData['amount']);
        
        return [
            'success' => true,
            'transaction_id' => 'TXN_' . uniqid(),
            'status' => $this->auto_approve ? 'approved' : 'pending',
            'amount' => $paymentData['amount'],
            'fee' => $fee['total_fee'],
            'net_amount' => $fee['net_amount'],
            'gateway_response' => [
                'gateway' => $this->code,
                'processed_at' => now()->toISOString(),
            ],
        ];
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        if (!$this->webhook_secret) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $this->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Handle webhook (placeholder for gateway-specific implementation)
     */
    public function handleWebhook($payload)
    {
        // This would be implemented differently for each gateway
        // For now, return a mock response
        
        return [
            'success' => true,
            'processed' => true,
            'message' => 'Webhook processed successfully',
        ];
    }

    /**
     * Get gateway configuration for frontend
     */
    public function getFrontendConfig()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'fee_percentage' => $this->fee_percentage,
            'fee_fixed' => $this->fee_fixed,
            'fee_charged_to_customer' => $this->fee_charged_to_customer,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'processing_time_days' => $this->processing_time_days,
            'supported_currencies' => $this->supported_currencies,
            'supported_countries' => $this->supported_countries,
        ];
    }
}