@extends('layouts.client')

@section('title', 'Referral Network')
@section('page-title', 'Referral Network')

@section('content')
@if(!$inNetwork)
    <!-- Not in Network -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-people display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">You're not in the referral network yet</h4>
                    <p class="text-muted">Join the referral network to start earning bonuses and building your team!</p>
                    <a href="{{ route('client.financial.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Network Overview -->
    <div class="row">
        <!-- Network Statistics -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Volume
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                R$ {{ number_format($statistics['total_volume'], 2, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Sponsored Clients
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $statistics['total_children'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Network Level
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                Level {{ $networkPosition->level }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-diagram-3" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Network Position
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ ucfirst($networkPosition->position) }} Leg
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-geo-alt" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Network Visualization -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>
                            Network Structure
                        </h6>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="showVolumeChart()">
                                <i class="bi bi-graph-up me-1"></i>
                                Volume
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showCountChart()">
                                <i class="bi bi-people me-1"></i>
                                Count
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="networkChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Network Information -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Network Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Your Position:</strong>
                        <span class="badge bg-primary ms-2">{{ ucfirst($networkPosition->position) }} Leg</span>
                    </div>
                    <div class="mb-3">
                        <strong>Network Level:</strong>
                        <span class="badge bg-info ms-2">Level {{ $networkPosition->level }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Sponsor:</strong>
                        <div class="mt-1">
                            @if($networkPosition->sponsor)
                                <span class="fw-bold">{{ $networkPosition->sponsor->name }}</span>
                                <br>
                                <small class="text-muted">{{ $networkPosition->sponsor->email }}</small>
                            @else
                                <span class="text-muted">No sponsor</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Left Leg:</strong>
                        <div class="mt-1">
                            <span class="badge bg-success">{{ $networkPosition->left_count }} clients</span>
                            <br>
                            <small class="text-muted">Volume: R$ {{ number_format($networkPosition->left_volume, 2, ',', '.') }}</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Right Leg:</strong>
                        <div class="mt-1">
                            <span class="badge bg-info">{{ $networkPosition->right_count }} clients</span>
                            <br>
                            <small class="text-muted">Volume: R$ {{ number_format($networkPosition->right_volume, 2, ',', '.') }}</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Network Status:</strong>
                        <span class="badge bg-{{ $networkPosition->is_active ? 'success' : 'secondary' }} ms-2">
                            {{ $networkPosition->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning-charge me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.network.visualization') }}" class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i>
                            View Full Network
                        </a>
                        <a href="{{ route('client.bonus.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-gift me-2"></i>
                            View Bonuses
                        </a>
                        <a href="{{ route('client.leads.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-people me-2"></i>
                            Manage Leads
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsored Clients -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-people me-2"></i>
                            Sponsored Clients
                        </h6>
                        <span class="badge bg-primary">{{ $sponsoredClients->flatten()->count() }} Total</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($sponsoredClients->flatten()->count() > 0)
                        <div class="row">
                            <!-- Left Leg -->
                            <div class="col-md-6">
                                <h6 class="text-success">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Left Leg ({{ $sponsoredClients->get('left', collect())->count() }})
                                </h6>
                                @if($sponsoredClients->has('left'))
                                    <div class="list-group">
                                        @foreach($sponsoredClients['left'] as $client)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $client->client->name }}</h6>
                                                <small>{{ $client->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <p class="mb-1 text-muted">{{ $client->client->email }}</p>
                                            <small>Level {{ $client->level }}</small>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No clients in left leg yet.</p>
                                @endif
                            </div>

                            <!-- Right Leg -->
                            <div class="col-md-6">
                                <h6 class="text-info">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    Right Leg ({{ $sponsoredClients->get('right', collect())->count() }})
                                </h6>
                                @if($sponsoredClients->has('right'))
                                    <div class="list-group">
                                        @foreach($sponsoredClients['right'] as $client)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $client->client->name }}</h6>
                                                <small>{{ $client->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <p class="mb-1 text-muted">{{ $client->client->email }}</p>
                                            <small>Level {{ $client->level }}</small>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No clients in right leg yet.</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">No sponsored clients yet</h5>
                            <p class="text-muted">Start referring clients to build your network!</p>
                            <a href="{{ route('client.leads.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Add Lead
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($inNetwork)
    // Network Chart
    const networkCtx = document.getElementById('networkChart').getContext('2d');
    
    let networkChart = new Chart(networkCtx, {
        type: 'doughnut',
        data: {
            labels: ['Left Leg', 'Right Leg'],
            datasets: [{
                data: [{{ $networkPosition->left_volume }}, {{ $networkPosition->right_volume }}],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(23, 162, 184, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            return label + ': R$ ' + value.toFixed(2);
                        }
                    }
                }
            }
        }
    });

    function showVolumeChart() {
        networkChart.data.datasets[0].data = [{{ $networkPosition->left_volume }}, {{ $networkPosition->right_volume }}];
        networkChart.update();
    }

    function showCountChart() {
        networkChart.data.datasets[0].data = [{{ $networkPosition->left_count }}, {{ $networkPosition->right_count }}];
        networkChart.update();
    }
    @endif
</script>
@endpush

<style>
.stat-card {
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.list-group-item {
    border-left: 4px solid transparent;
}

.list-group-item:hover {
    border-left-color: var(--bs-primary);
}
</style>
@endsection
