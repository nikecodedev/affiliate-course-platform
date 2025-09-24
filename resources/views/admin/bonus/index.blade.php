@extends('layouts.admin')

@section('title', 'Bonus Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-gift me-2"></i>
                        Bonus Settings Configuration
                    </h3>
                </div>

                <div class="card-body">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        @foreach($bonusTypes as $type => $name)
                            @php
                                $setting = $bonusSettings[$type];
                                $levels = $setting->getConfiguredLevels();
                                $levelCount = count($levels);
                            @endphp
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 {{ $setting->is_active ? 'border-success' : 'border-secondary' }}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-{{ $type === 'direct_referral' ? 'handshake' : ($type === 'unilevel' ? 'sitemap' : 'th') }} me-2"></i>
                                            {{ $name }}
                                        </h5>
                                        <form action="{{ route('admin.bonus.toggle', $type) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $setting->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Status:</strong>
                                            <span class="badge {{ $setting->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        @if($type === 'direct_referral')
                                            <div class="mb-2">
                                                <strong>Payment Mode:</strong>
                                                <span class="badge bg-info">{{ $setting->getPaymentModeDisplayName() }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Amount:</strong>
                                                {{ $setting->getFormattedLevelConfig(1) }}
                                            </div>
                                        @elseif($type === 'unilevel')
                                            <div class="mb-2">
                                                <strong>Levels Configured:</strong>
                                                <span class="badge bg-primary">{{ $levelCount }}</span>
                                            </div>
                                            @if($levelCount > 0)
                                                <div class="mb-2">
                                                    <strong>Levels:</strong>
                                                    <div class="mt-1">
                                                        @foreach($levels as $level => $config)
                                                            <small class="d-block">
                                                                Level {{ $level }}: {{ $setting->getFormattedLevelConfig($level) }}
                                                            </small>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @elseif($type === 'matrix')
                                            <div class="mb-2">
                                                <strong>Matrix Size:</strong>
                                                <span class="badge bg-warning">{{ $setting->width ?? 'N/A' }}x{{ $setting->depth ?? 'N/A' }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Levels Configured:</strong>
                                                <span class="badge bg-primary">{{ $levelCount }}</span>
                                            </div>
                                            @if($levelCount > 0)
                                                <div class="mb-2">
                                                    <strong>Levels:</strong>
                                                    <div class="mt-1">
                                                        @foreach($levels as $level => $config)
                                                            <small class="d-block">
                                                                Level {{ $level }}: {{ $setting->getFormattedLevelConfig($level) }}
                                                            </small>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        @if($levelCount === 0 && $type !== 'direct_referral')
                                            <div class="alert alert-warning alert-sm">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                No levels configured
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('admin.bonus.edit', $type) }}" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-edit me-1"></i>
                                            Configure {{ $name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Information Card -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle me-2"></i>
                                        How Bonus System Works
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6>Direct Referral</h6>
                                            <p class="small text-muted">
                                                Always paid when someone you referred makes a purchase, regardless of invoice status.
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Unilevel</h6>
                                            <p class="small text-muted">
                                                Paid to your downline network based on configured levels. Requires active invoice.
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Matrix</h6>
                                            <p class="small text-muted">
                                                Paid based on matrix structure with defined width and depth. Requires active invoice.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
