@extends('layouts.client')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total de Leads</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_leads']) }}</h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> {{ $stats['active_leads'] }} ativos
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people display-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-1">Ganhos Totais</h6>
                        <h3 class="mb-0">R$ {{ number_format($stats['total_earnings'], 2, ',', '.') }}</h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> Este mês
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-cash-stack display-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-1">Saldo Disponível</h6>
                        <h3 class="mb-0">R$ {{ number_format($stats['available_balance'], 2, ',', '.') }}</h3>
                        <small class="text-info">
                            <i class="bi bi-wallet2"></i> Para saque
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-wallet2 display-4 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-1">Tickets Abertos</h6>
                        <h3 class="mb-0">{{ $stats['open_tickets'] }}</h3>
                        <small class="text-warning">
                            <i class="bi bi-headset"></i> Aguardando
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-headset display-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Active Invoice Progress -->
    @if($activeInvoice)
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-receipt me-2"></i>Fatura Ativa
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Plano</h6>
                        <h5 class="mb-3">{{ $activeInvoice->plan->name ?? 'N/A' }}</h5>
                        
                        <h6 class="text-muted mb-2">Valor</h6>
                        <h5 class="mb-3 text-success">R$ {{ number_format($activeInvoice->amount, 2, ',', '.') }}</h5>
                        
                        <h6 class="text-muted mb-2">Status</h6>
                        <span class="badge bg-success fs-6">Ativa</span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Progresso do Tempo</h6>
                        <div class="progress mb-3" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $activeInvoice->progress_percentage }}%"
                                 aria-valuenow="{{ $activeInvoice->progress_percentage }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                                {{ $activeInvoice->progress_percentage }}%
                            </div>
                        </div>
                        
                        <h6 class="text-muted mb-2">Dias Restantes</h6>
                        <h5 class="mb-3 text-warning">{{ $activeInvoice->days_remaining }} dias</h5>
                        
                        <h6 class="text-muted mb-2">Expira em</h6>
                        <h6 class="mb-0">{{ $activeInvoice->expires_at->format('d/m/Y') }}</h6>
                    </div>
                </div>

                @if(optional($activeInvoice->plan)->external_product_url || optional($activeInvoice->plan)->courses()->exists())
                <hr>
                <div class="row">
                    @if(optional($activeInvoice->plan)->external_product_url)
                    <div class="col-md-6 mb-3">
                        <a href="{{ $activeInvoice->plan->external_product_url }}" target="_blank" class="btn btn-success w-100">
                            <i class="bi bi-download me-2"></i>Download Product
                        </a>
                    </div>
                    @endif
                    @if(optional($activeInvoice->plan)->courses()->exists())
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Linked Courses</h6>
                        <ul class="list-unstyled mb-0">
                            @foreach($activeInvoice->plan->courses as $course)
                                <li class="mb-1">
                                    <a href="{{ route('training.show', $course) }}" class="text-decoration-none">
                                        <i class="bi bi-book me-1"></i>{{ $course->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    <!-- Course Access -->
    @if($client->hasCourseAccess())
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-book me-2"></i>Course Access
                </h5>
            </div>
            <div class="card-body text-center">
                <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                <h6 class="mb-2">Access Granted!</h6>
                <p class="text-muted mb-3">You have full access to the training area.</p>
                <a href="{{ route('client.training.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-right me-2"></i>
                    Access Courses
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-book me-2"></i>Course Access
                </h5>
            </div>
            <div class="card-body text-center">
                <i class="bi bi-lock display-4 text-muted mb-3"></i>
                <h6 class="mb-2">Access Restricted</h6>
                <p class="text-muted mb-3">Purchase a plan to access the courses.</p>
                <a href="{{ route('client.financial.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-right me-2"></i>
                    View Plans
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row">
    <!-- Recent Transactions -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>Recent Transactions
                    </h5>
                    <a href="{{ route('client.financial.transactions') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentTransactions->count() > 0)
                    @foreach($recentTransactions as $transaction)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">{{ $transaction->description }}</h6>
                            <small class="text-muted">{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->formatted_amount }}
                            </span>
                            <br>
                            <span class="badge bg-{{ $transaction->status_badge_color }}">
                                {{ $transaction->status_badge_text }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2">Nenhuma transação encontrada</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Leads -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Recent Leads
                    </h5>
                    <a href="{{ route('client.leads.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentLeads->count() > 0)
                    @foreach($recentLeads as $lead)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">{{ $lead->name }}</h6>
                            <small class="text-muted">{{ $lead->email }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $lead->status_badge_color }}">
                                {{ $lead->status_badge_text }}
                            </span>
                            <br>
                            <small class="text-muted">{{ $lead->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-people display-4"></i>
                        <p class="mt-2">Nenhum lead encontrado</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Monthly Earnings Chart -->
@if(isset($monthlyEarnings))
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Ganhos dos Últimos 12 Meses
                </h5>
            </div>
            <div class="card-body">
                <canvas id="earningsChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Capture Sites -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-link-45deg me-2"></i>Sites de Captura
                    </h5>
                    <a href="{{ route('client.capture-sites') }}" class="btn btn-sm btn-outline-primary">
                        Gerenciar Sites
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-3">
                            <h6 class="mb-2">
                                <i class="bi bi-globe me-2"></i>Site Principal
                            </h6>
                            <p class="text-muted mb-2">Site de captura com seus tracking tags</p>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" 
                                       value="{{ route('client.capture.main', ['code' => $client->id]) }}" 
                                       readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                        onclick="copyToClipboard(this)">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-3">
                            <h6 class="mb-2">
                                <i class="bi bi-globe2 me-2"></i>Site Secundário
                            </h6>
                            <p class="text-muted mb-2">Site de captura alternativo</p>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" 
                                       value="{{ route('client.capture.secondary', ['code' => $client->id]) }}" 
                                       readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                        onclick="copyToClipboard(this)">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Earnings Chart
@if(isset($monthlyEarnings))
const ctx = document.getElementById('earningsChart').getContext('2d');
const earningsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($monthlyEarnings['months']),
        datasets: [{
            label: 'Ganhos (R$)',
            data: @json($monthlyEarnings['earnings']),
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3498db',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});
@endif

// Copy to clipboard function
function copyToClipboard(button) {
    const input = button.parentElement.querySelector('input');
    input.select();
    input.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check"></i>';
    button.classList.remove('btn-outline-secondary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}

// Auto-refresh stats every 30 seconds
setInterval(function() {
    fetch('{{ route("client.quick-stats") }}')
        .then(response => response.json())
        .then(data => {
            // Update stats cards here if needed
            console.log('Stats updated:', data);
        })
        .catch(error => console.error('Error updating stats:', error));
}, 30000);
</script>
@endsection
