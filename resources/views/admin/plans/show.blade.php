@extends('layouts.admin')

@section('title', 'Plan Details: ' . $plan->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye me-2"></i>
                        Plan Details: {{ $plan->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Edit Plan
                        </a>
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Plans
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Plan Image -->
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                @if($plan->image)
                                    <img src="{{ asset('storage/' . $plan->image) }}" 
                                         alt="{{ $plan->title }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 300px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                         style="height: 300px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Plan Information -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Title:</strong></td>
                                            <td>{{ $plan->title }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $plan->type === 'digital' ? 'info' : ($plan->type === 'physical' ? 'warning' : 'success') }}">
                                                    {{ ucfirst($plan->type) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge {{ $plan->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $plan->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Course:</strong></td>
                                            <td>
                                                @if($plan->course)
                                                    {{ $plan->course->title }}
                                                @else
                                                    <span class="text-muted">No course assigned</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>External URL:</strong></td>
                                            <td>
                                                @if($plan->external_url)
                                                    <a href="{{ $plan->external_url }}" target="_blank" class="text-decoration-none">
                                                        {{ $plan->external_url }} <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">No external URL</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Pricing</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Sale Price:</strong></td>
                                            <td><span class="h5 text-success">R$ {{ number_format($plan->sale_price, 2, ',', '.') }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cost Price:</strong></td>
                                            <td>
                                                @if($plan->cost_price)
                                                    R$ {{ number_format($plan->cost_price, 2, ',', '.') }}
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Profit Margin:</strong></td>
                                            <td>
                                                @if($plan->cost_price)
                                                    @php
                                                        $margin = (($plan->sale_price - $plan->cost_price) / $plan->sale_price) * 100;
                                                    @endphp
                                                    <span class="text-{{ $margin > 0 ? 'success' : 'danger' }}">
                                                        {{ number_format($margin, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($plan->description)
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3">Description</h5>
                                        <p class="text-muted">{{ $plan->description }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Commission Configuration -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-percentage me-2"></i>
                                Commission Configuration
                            </h5>
                        </div>

                        <!-- Direct Referral Bonus -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-handshake me-2"></i>
                                        Direct Referral Bonus
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($plan->direct_bonus_enabled)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="fw-bold">{{ $plan->getFormattedDirectReferralBonusAttribute() }}</span>
                                            <span class="ms-2 badge bg-info">{{ ucfirst($plan->direct_bonus_mode) }}</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <span class="text-muted">Disabled</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Profit Sharing -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-share-alt me-2"></i>
                                        Profit Sharing
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($plan->commission_profit_sharing)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-percentage text-primary me-2"></i>
                                            <span class="fw-bold">{{ $plan->commission_profit_sharing }}%</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <span class="text-muted">Not configured</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Unilevel Commission -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-sitemap me-2"></i>
                                        Unilevel Commission
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $unilevelConfig = $plan->getUnilevelConfig();
                                    @endphp
                                    @if(!empty($unilevelConfig))
                                        <div class="row">
                                            @foreach($unilevelConfig as $level => $config)
                                                <div class="col-6 mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Level {{ $level }}:</span>
                                                        <span class="fw-bold">
                                                            @if($config['mode'] === 'percentage')
                                                                {{ $config['value'] }}%
                                                            @else
                                                                R$ {{ number_format($config['value'], 2, ',', '.') }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <span class="text-muted">Not configured</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Matrix Commission -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-th me-2"></i>
                                        Matrix Commission
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $matrixConfig = $plan->getMatrixConfig();
                                    @endphp
                                    @if(!empty($matrixConfig['levels']))
                                        <div class="mb-2">
                                            <strong>Matrix Size:</strong> 
                                            <span class="badge bg-warning">{{ $matrixConfig['width'] }}x{{ $matrixConfig['depth'] }}</span>
                                        </div>
                                        <div class="row">
                                            @foreach($matrixConfig['levels'] as $level => $value)
                                                <div class="col-6 mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Level {{ $level }}:</span>
                                                        <span class="fw-bold">{{ $value }}%</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <span class="text-muted">Not configured</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>
                                Plan Statistics
                            </h5>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-shopping-cart fa-2x text-primary mb-2"></i>
                                    <h5 class="card-title">{{ $plan->invoices()->count() }}</h5>
                                    <p class="card-text text-muted">Total Sales</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                                    <h5 class="card-title">R$ {{ number_format($plan->invoices()->sum('amount'), 2, ',', '.') }}</h5>
                                    <p class="card-text text-muted">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                    <h5 class="card-title">{{ $plan->created_at->format('M d, Y') }}</h5>
                                    <p class="card-text text-muted">Created</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                    <h5 class="card-title">{{ $plan->updated_at->diffForHumans() }}</h5>
                                    <p class="card-text text-muted">Last Updated</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back to Plans
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>
                                Edit Plan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

