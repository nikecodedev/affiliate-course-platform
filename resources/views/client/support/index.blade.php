@extends('layouts.client')

@section('title', 'Support')
@section('page-title', 'Support Center')

@section('content')
<div class="row">
    <!-- Support Statistics -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Tickets
                        </div>
                        <div class="stat-number">{{ $client->supportTickets()->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-ticket-perforated" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Open Tickets
                        </div>
                        <div class="stat-number">{{ $client->supportTickets()->where('status', 'open')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-exclamation-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Closed Tickets
                        </div>
                        <div class="stat-number">{{ $client->supportTickets()->where('status', 'closed')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Response Time
                        </div>
                        <div class="stat-number">{{ $avgResponseTime ?? '0' }}h</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-charge me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('client.support.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create New Ticket
                    </a>
                    <a href="{{ route('client.support.faq') }}" class="btn btn-outline-primary">
                        <i class="bi bi-question-circle me-2"></i>
                        View FAQ
                    </a>
                    <a href="mailto:support@example.com" class="btn btn-outline-secondary">
                        <i class="bi bi-envelope me-2"></i>
                        Email Support
                    </a>
                    <a href="tel:+1234567890" class="btn btn-outline-info">
                        <i class="bi bi-telephone me-2"></i>
                        Call Support
                    </a>
                </div>
            </div>
        </div>

        <!-- Support Hours -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-clock me-2"></i>
                    Support Hours
                </h6>
            </div>
            <div class="card-body">
                <div class="support-hours">
                    <div class="d-flex justify-content-between">
                        <span>Monday - Friday</span>
                        <span class="fw-bold">9:00 AM - 6:00 PM</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Saturday</span>
                        <span class="fw-bold">10:00 AM - 4:00 PM</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Sunday</span>
                        <span class="fw-bold text-muted">Closed</span>
                    </div>
                </div>
                <hr>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Emergency support available 24/7 for critical issues.
                </small>
            </div>
        </div>
    </div>

    <!-- Support Tickets -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-ticket-perforated me-2"></i>
                        Support Tickets
                    </h5>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="status-filter" id="all" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="all">All</label>

                        <input type="radio" class="btn-check" name="status-filter" id="open" autocomplete="off">
                        <label class="btn btn-outline-warning btn-sm" for="open">Open</label>

                        <input type="radio" class="btn-check" name="status-filter" id="closed" autocomplete="off">
                        <label class="btn btn-outline-success btn-sm" for="closed">Closed</label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($tickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Last Update</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                <tr>
                                    <td>
                                        <code class="text-primary">#{{ $ticket->id }}</code>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ Str::limit($ticket->subject, 40) }}</span>
                                            <small class="text-muted">{{ Str::limit($ticket->description, 60) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $ticket->category ?? 'General' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'info') }}">
                                            {{ ucfirst($ticket->priority ?? 'low') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'closed' ? 'success' : 'info') }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $ticket->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $ticket->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $ticket->updated_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $ticket->updated_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('client.support.ticket.show', $ticket) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($ticket->status === 'open')
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="closeTicket({{ $ticket->id }})"
                                                    data-bs-toggle="tooltip" title="Close Ticket">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} 
                                of {{ $tickets->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $tickets->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-ticket-perforated display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No support tickets found</h4>
                        <p class="text-muted">You haven't created any support tickets yet.</p>
                        <a href="{{ route('client.support.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create Your First Ticket
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Close Ticket Modal -->
<div class="modal fade" id="closeTicketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Close Support Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to close this support ticket?</p>
                <p class="text-muted">
                    <i class="bi bi-warning me-2"></i>
                    This action cannot be undone. You can still view the ticket history.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="closeTicketForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check me-2"></i>
                        Close Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card .card-body {
    padding: 1.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
}

.support-hours .d-flex {
    margin-bottom: 0.5rem;
}
</style>

@push('scripts')
<script>
    function closeTicket(ticketId) {
        const modal = new bootstrap.Modal(document.getElementById('closeTicketModal'));
        const form = document.getElementById('closeTicketForm');
        
        form.action = `/client/support/tickets/${ticketId}/close`;
        modal.show();
    }
    
    // Filter tickets by status
    document.querySelectorAll('input[name="status-filter"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const status = this.id;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const statusBadge = row.querySelector('.badge');
                const ticketStatus = statusBadge.textContent.toLowerCase().trim();
                
                if (status === 'all' || ticketStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
