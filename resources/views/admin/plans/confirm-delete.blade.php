@extends('layouts.admin')

@section('title', 'Confirm Plan Deletion')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        Confirm Plan Deletion
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> Warning!</h5>
                        <p>You are about to delete the plan <strong>"{{ $plan->title }}"</strong>. This action cannot be undone.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Plan Details:</h5>
                            <ul class="list-unstyled">
                                <li><strong>Title:</strong> {{ $plan->title }}</li>
                                <li><strong>Type:</strong> {{ ucfirst($plan->type) }}</li>
                                <li><strong>Price:</strong> {{ $plan->formatted_sale_price }}</li>
                                <li><strong>Status:</strong> 
                                    <span class="badge badge-{{ $plan->status ? 'success' : 'danger' }}">
                                        {{ $plan->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Related Records:</h5>
                            <ul class="list-unstyled">
                                @if($relatedCounts['invoices'] > 0)
                                    <li class="text-danger">
                                        <i class="fas fa-file-invoice"></i> 
                                        {{ $relatedCounts['invoices'] }} Invoice(s)
                                    </li>
                                @endif
                                @if($relatedCounts['sales'] > 0)
                                    <li class="text-danger">
                                        <i class="fas fa-shopping-cart"></i> 
                                        {{ $relatedCounts['sales'] }} Sale(s)
                                    </li>
                                @endif
                                @if($relatedCounts['products'] > 0)
                                    <li class="text-danger">
                                        <i class="fas fa-box"></i> 
                                        {{ $relatedCounts['products'] }} Product(s)
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    @if(array_sum($relatedCounts) > 0)
                        <div class="mt-4">
                            <h5>Choose how to handle related records:</h5>
                            <form action="{{ route('admin.plans.delete-with-options', $plan) }}" method="POST" id="deleteForm">
                                @csrf
                                @method('POST')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Invoices ({{ $relatedCounts['invoices'] }}):</h6>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="handle_invoices" id="invoices_delete" value="delete" required>
                                                <label class="form-check-label" for="invoices_delete">
                                                    Delete all invoices
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="handle_invoices" id="invoices_transfer" value="transfer" required>
                                                <label class="form-check-label" for="invoices_transfer">
                                                    Transfer to another plan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Sales ({{ $relatedCounts['sales'] }}):</h6>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="handle_sales" id="sales_delete" value="delete" required>
                                                <label class="form-check-label" for="sales_delete">
                                                    Delete all sales
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="handle_sales" id="sales_transfer" value="transfer" required>
                                                <label class="form-check-label" for="sales_transfer">
                                                    Transfer to another plan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="transferPlanGroup" style="display: none;">
                                    <label for="transfer_plan_id">Transfer to Plan:</label>
                                    <select class="form-control" name="transfer_plan_id" id="transfer_plan_id">
                                        <option value="">Select a plan...</option>
                                        @foreach(\App\Models\Plan::where('id', '!=', $plan->id)->get() as $otherPlan)
                                            <option value="{{ $otherPlan->id }}">{{ $otherPlan->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <div>
                            @if(array_sum($relatedCounts) > 0)
                                <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm').submit()">
                                    <i class="fas fa-trash"></i> Delete with Options
                                </button>
                                <a href="{{ route('admin.plans.force-delete', $plan) }}" 
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('Are you sure you want to delete this plan and ALL related records? This action cannot be undone!')">
                                    <i class="fas fa-exclamation-triangle"></i> Force Delete All
                                </a>
                            @else
                                <a href="{{ route('admin.plans.force-delete', $plan) }}" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this plan? This action cannot be undone!')">
                                    <i class="fas fa-trash"></i> Delete Plan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const transferRadios = document.querySelectorAll('input[name="handle_invoices"][value="transfer"], input[name="handle_sales"][value="transfer"]');
    const transferPlanGroup = document.getElementById('transferPlanGroup');
    const transferPlanSelect = document.getElementById('transfer_plan_id');
    
    function toggleTransferPlan() {
        const anyTransferSelected = Array.from(transferRadios).some(radio => radio.checked);
        transferPlanGroup.style.display = anyTransferSelected ? 'block' : 'none';
        if (anyTransferSelected) {
            transferPlanSelect.required = true;
        } else {
            transferPlanSelect.required = false;
        }
    }
    
    transferRadios.forEach(radio => {
        radio.addEventListener('change', toggleTransferPlan);
    });
    
    // Initial check
    toggleTransferPlan();
});
</script>
@endsection
