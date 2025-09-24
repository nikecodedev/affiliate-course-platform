@extends('layouts.admin')

@section('title', 'Plans Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-box me-2"></i>
                        Plans Management
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create New Plan
                        </a>
                    </div>
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

                    @if($plans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Direct Bonus</th>
                                        <th>Commissions</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plans as $plan)
                                        <tr>
                                            <td>
                                                @if($plan->image)
                                                    <img src="{{ asset('storage/' . $plan->image) }}" 
                                                         alt="{{ $plan->title }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $plan->title }}</strong>
                                                    @if($plan->course)
                                                        <br><small class="text-muted">Course: {{ $plan->course->title }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $plan->type === 'digital' ? 'info' : ($plan->type === 'physical' ? 'warning' : 'success') }}">
                                                    {{ ucfirst($plan->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>R$ {{ number_format($plan->sale_price, 2, ',', '.') }}</strong>
                                                    @if($plan->cost_price)
                                                        <br><small class="text-muted">Cost: R$ {{ number_format($plan->cost_price, 2, ',', '.') }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($plan->direct_bonus_enabled)
                                                    <span class="badge bg-success">
                                                        {{ $plan->getFormattedDirectReferralBonusAttribute() }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Disabled</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $commissionSummary = $plan->getCommissionSummary();
                                                @endphp
                                                @if(!empty($commissionSummary))
                                                    <div class="small">
                                                        @foreach($commissionSummary as $type => $value)
                                                            <div>
                                                                <strong>{{ ucfirst(str_replace('_', ' ', $type)) }}:</strong> {{ $value }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">Not configured</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $plan->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $plan->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.plans.show', $plan) }}" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.plans.edit', $plan) }}" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Edit Plan">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-{{ $plan->status ? 'warning' : 'success' }} btn-sm toggle-status"
                                                            data-plan-id="{{ $plan->id }}"
                                                            title="{{ $plan->status ? 'Deactivate' : 'Activate' }} Plan">
                                                        <i class="fas fa-{{ $plan->status ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                    <form action="{{ route('admin.plans.destroy', $plan) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                title="Delete Plan">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $plans->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No plans found</h5>
                            <p class="text-muted">Create your first plan to get started.</p>
                            <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create New Plan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            const originalIcon = this.querySelector('i');
            const originalClass = this.className;
            
            // Show loading state
            this.disabled = true;
            originalIcon.className = 'fas fa-spinner fa-spin';
            
            fetch(`/admin/plans/${planId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    if (data.status) {
                        this.className = originalClass.replace('outline-success', 'outline-warning');
                        this.title = 'Deactivate Plan';
                        originalIcon.className = 'fas fa-pause';
                    } else {
                        this.className = originalClass.replace('outline-warning', 'outline-success');
                        this.title = 'Activate Plan';
                        originalIcon.className = 'fas fa-play';
                    }
                    
                    // Update status badge in the same row
                    const row = this.closest('tr');
                    const statusBadge = row.querySelector('.badge');
                    if (data.status) {
                        statusBadge.className = 'badge bg-success';
                        statusBadge.textContent = 'Active';
                    } else {
                        statusBadge.className = 'badge bg-danger';
                        statusBadge.textContent = 'Inactive';
                    }
                    
                    // Show success message
                    showAlert('Plan status updated successfully!', 'success');
                } else {
                    showAlert('Failed to update plan status', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error updating plan status', 'danger');
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
    
    // Alert function
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.card-body');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endsection