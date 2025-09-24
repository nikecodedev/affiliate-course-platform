@extends('layouts.admin')

@section('title', 'View Bonus Configuration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye me-2"></i>
                        Bonus Configuration: {{ $globalBonusConfiguration->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.bonus-configurations.global.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Configurations
                        </a>
                        <a href="{{ route('admin.bonus-configurations.global.edit', $globalBonusConfiguration) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Edit Configuration
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Configuration Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Configuration Details
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Basic Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $globalBonusConfiguration->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type:</strong></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $globalBonusConfiguration->type)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge {{ $globalBonusConfiguration->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $globalBonusConfiguration->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($globalBonusConfiguration->description)
                                        <tr>
                                            <td><strong>Description:</strong></td>
                                            <td>{{ $globalBonusConfiguration->description }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Configuration Settings</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Max Depth:</strong></td>
                                            <td><span class="badge bg-primary">{{ $globalBonusConfiguration->max_depth }}</span></td>
                                        </tr>
                                        @if($globalBonusConfiguration->type === 'forced_matrix')
                                        <tr>
                                            <td><strong>Max Width:</strong></td>
                                            <td><span class="badge bg-warning">{{ $globalBonusConfiguration->max_width }}</span></td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Min Sale Amount:</strong></td>
                                            <td>R$ {{ number_format($globalBonusConfiguration->min_sale_amount, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Default Type:</strong></td>
                                            <td>
                                                <span class="badge {{ $globalBonusConfiguration->is_percentage ? 'bg-info' : 'bg-secondary' }}">
                                                    {{ $globalBonusConfiguration->is_percentage ? 'Percentage' : 'Fixed Amount' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Requires Active Invoice:</strong></td>
                                            <td>
                                                <span class="badge {{ $globalBonusConfiguration->requires_active_invoice ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $globalBonusConfiguration->requires_active_invoice ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bonus Levels -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-layer-group me-2"></i>
                                Bonus Levels ({{ $globalBonusConfiguration->bonusLevels->count() }} levels)
                            </h5>
                        </div>
                        <div class="col-12">
                            @if($globalBonusConfiguration->bonusLevels->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Level</th>
                                                <th>Amount</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($globalBonusConfiguration->bonusLevels->sortBy('level') as $level)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary">Level {{ $level->level }}</span>
                                                    </td>
                                                    <td>
                                                        <strong>
                                                            @if($level->is_percentage)
                                                                {{ $level->amount }}%
                                                            @else
                                                                R$ {{ number_format($level->amount, 2, ',', '.') }}
                                                            @endif
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $level->is_percentage ? 'bg-info' : 'bg-secondary' }}">
                                                            {{ $level->is_percentage ? 'Percentage' : 'Fixed Amount' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $level->description ?: 'No description' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $level->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $level->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No bonus levels configured for this configuration.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Configuration Summary -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>
                                Configuration Summary
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $globalBonusConfiguration->bonusLevels->count() }}</h4>
                                    <p class="mb-0">Total Levels</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $globalBonusConfiguration->bonusLevels->where('is_active', true)->count() }}</h4>
                                    <p class="mb-0">Active Levels</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>
                                        @if($globalBonusConfiguration->bonusLevels->where('is_percentage', false)->count() > 0)
                                            {{ $globalBonusConfiguration->bonusLevels->where('is_percentage', false)->sum('amount') }}
                                        @else
                                            N/A
                                        @endif
                                    </h4>
                                    <p class="mb-0">Total Fixed Amount</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('admin.bonus-configurations.global.toggle', $globalBonusConfiguration) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn {{ $globalBonusConfiguration->is_active ? 'btn-warning' : 'btn-success' }}">
                                    <i class="fas fa-power-off me-1"></i>
                                    {{ $globalBonusConfiguration->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.bonus-configurations.global.edit', $globalBonusConfiguration) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>
                                Edit Configuration
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
