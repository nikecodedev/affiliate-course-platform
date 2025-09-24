@extends('layouts.admin')

@section('title', 'Create Payment Gateway')
@section('page-title', 'Create New Payment Gateway')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Gateway Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.gateways.payment.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Gateway Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Gateway Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" required>
                            <div class="form-text">Unique identifier for the gateway (e.g., asaas, stone)</div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Payment Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Payment Type</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="api_url" class="form-label">API URL</label>
                            <input type="url" class="form-control @error('api_url') is-invalid @enderror" 
                                   id="api_url" name="api_url" value="{{ old('api_url') }}">
                            @error('api_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="webhook_url" class="form-label">Webhook URL</label>
                            <input type="url" class="form-control @error('webhook_url') is-invalid @enderror" 
                                   id="webhook_url" name="webhook_url" value="{{ old('webhook_url') }}">
                            @error('webhook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- API Credentials -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">API Credentials</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="api_key" class="form-label">API Key</label>
                                    <input type="text" class="form-control @error('api_key') is-invalid @enderror" 
                                           id="api_key" name="api_key" value="{{ old('api_key') }}">
                                    @error('api_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="api_secret" class="form-label">API Secret</label>
                                    <input type="password" class="form-control @error('api_secret') is-invalid @enderror" 
                                           id="api_secret" name="api_secret" value="{{ old('api_secret') }}">
                                    @error('api_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="webhook_secret" class="form-label">Webhook Secret</label>
                                    <input type="password" class="form-control @error('webhook_secret') is-invalid @enderror" 
                                           id="webhook_secret" name="webhook_secret" value="{{ old('webhook_secret') }}">
                                    @error('webhook_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Configuration -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Fee Configuration</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="fee_percentage" class="form-label">Fee Percentage (%)</label>
                                    <input type="number" class="form-control @error('fee_percentage') is-invalid @enderror" 
                                           id="fee_percentage" name="fee_percentage" value="{{ old('fee_percentage', 0) }}" 
                                           step="0.01" min="0" max="100">
                                    @error('fee_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="fee_fixed" class="form-label">Fixed Fee (R$)</label>
                                    <input type="number" class="form-control @error('fee_fixed') is-invalid @enderror" 
                                           id="fee_fixed" name="fee_fixed" value="{{ old('fee_fixed', 0) }}" 
                                           step="0.01" min="0">
                                    @error('fee_fixed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="fee_charged_to_customer" 
                                               name="fee_charged_to_customer" value="1" {{ old('fee_charged_to_customer') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fee_charged_to_customer">
                                            Charge fee to customer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Processing Settings -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Processing Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="processing_time_days" class="form-label">Processing Time (Days)</label>
                                    <input type="number" class="form-control @error('processing_time_days') is-invalid @enderror" 
                                           id="processing_time_days" name="processing_time_days" value="{{ old('processing_time_days', 1) }}" 
                                           min="0">
                                    @error('processing_time_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="auto_approve" 
                                               name="auto_approve" value="1" {{ old('auto_approve') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auto_approve">
                                            Auto approve payments
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_webhook" 
                                               name="requires_webhook" value="1" {{ old('requires_webhook') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_webhook">
                                            Requires webhook
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Limits -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Amount Limits</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="min_amount" class="form-label">Minimum Amount (R$)</label>
                                    <input type="number" class="form-control @error('min_amount') is-invalid @enderror" 
                                           id="min_amount" name="min_amount" value="{{ old('min_amount') }}" 
                                           step="0.01" min="0">
                                    @error('min_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_amount" class="form-label">Maximum Amount (R$)</label>
                                    <input type="number" class="form-control @error('max_amount') is-invalid @enderror" 
                                           id="max_amount" name="max_amount" value="{{ old('max_amount') }}" 
                                           step="0.01" min="0">
                                    @error('max_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supported Currencies and Countries -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Supported Options</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Supported Currencies</label>
                                    <div class="row">
                                        @foreach($currencies as $code => $name)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="currency_{{ $code }}" name="supported_currencies[]" 
                                                       value="{{ $code }}" {{ in_array($code, old('supported_currencies', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="currency_{{ $code }}">
                                                    {{ $code }} - {{ $name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Supported Countries</label>
                                    <div class="row">
                                        @foreach($countries as $code => $name)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="country_{{ $code }}" name="supported_countries[]" 
                                                       value="{{ $code }}" {{ in_array($code, old('supported_countries', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="country_{{ $code }}">
                                                    {{ $code }} - {{ $name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.gateways.payment.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Gateways
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Create Gateway
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Help Panel -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Gateway Configuration Help
                </h6>
            </div>
            <div class="card-body">
                <h6>API Credentials</h6>
                <p class="text-muted small">
                    Provide the API key and secret from your payment gateway provider. 
                    These will be encrypted and stored securely.
                </p>

                <h6>Fee Configuration</h6>
                <p class="text-muted small">
                    Set the percentage and fixed fees for this gateway. You can choose 
                    to charge these fees to the customer or absorb them.
                </p>

                <h6>Processing Settings</h6>
                <p class="text-muted small">
                    Configure how payments are processed and approved. Auto-approve 
                    enables instant payment confirmation.
                </p>

                <h6>Amount Limits</h6>
                <p class="text-muted small">
                    Set minimum and maximum transaction amounts to control 
                    the gateway usage.
                </p>

                <h6>Supported Options</h6>
                <p class="text-muted small">
                    Select which currencies and countries this gateway supports. 
                    This helps in payment method selection.
                </p>
            </div>
        </div>

        <!-- Gateway Types Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-list me-2"></i>
                    Gateway Types
                </h6>
            </div>
            <div class="card-body">
                <div class="small">
                    <div class="mb-2">
                        <strong>Credit Card:</strong> Traditional credit/debit card payments
                    </div>
                    <div class="mb-2">
                        <strong>PIX:</strong> Brazilian instant payment system
                    </div>
                    <div class="mb-2">
                        <strong>Boleto:</strong> Brazilian bank slip payment
                    </div>
                    <div class="mb-2">
                        <strong>Bank Transfer:</strong> Direct bank transfer payments
                    </div>
                    <div class="mb-2">
                        <strong>Cryptocurrency:</strong> Digital currency payments
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate code from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const code = name.toLowerCase()
            .replace(/[^a-z0-9]/g, '_')
            .replace(/_+/g, '_')
            .replace(/^_|_$/g, '');
        
        if (!document.getElementById('code').value) {
            document.getElementById('code').value = code;
        }
    });
</script>
@endpush
@endsection
