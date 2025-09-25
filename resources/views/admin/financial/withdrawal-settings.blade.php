@extends('layouts.admin')

@section('title', 'Withdrawal Settings')
@section('page-title', 'Withdrawal Settings')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear me-2"></i>Withdrawal Rules
                </h5>
            </div>
            <form method="POST" action="{{ route('admin.financial.withdrawal-settings.update') }}">
                @csrf
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Available Days</label>
                        <div class="row">
                            @php
                                $days = ['mon' => 'Monday','tue' => 'Tuesday','wed' => 'Wednesday','thu' => 'Thursday','fri' => 'Friday','sat' => 'Saturday','sun' => 'Sunday'];
                                $selectedDays = $settings->available_days ?? [];
                            @endphp
                            @foreach($days as $key => $label)
                                <div class="col-6 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="day_{{ $key }}" name="available_days[]" value="{{ $key }}" {{ in_array($key, $selectedDays) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="day_{{ $key }}">{{ $label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Available Times (HH:MM ranges)</label>
                        @php $times = $settings->available_times ?? []; @endphp
                        <div id="times-container">
                            @forelse($times as $t)
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col"><input type="time" class="form-control" name="available_times[]" value="{{ $t }}"></div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.row').remove()"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            @empty
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col"><input type="time" class="form-control" name="available_times[]"></div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.row').remove()"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addTimeRow()"><i class="bi bi-plus"></i> Add Time</button>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fee Type</label>
                                <select class="form-select" name="fee_type">
                                    <option value="fixed" {{ ($settings->fee_type ?? 'fixed') === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    <option value="percent" {{ ($settings->fee_type ?? 'fixed') === 'percent' ? 'selected' : '' }}>Percent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fee Value</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="fee_value" value="{{ $settings->fee_value ?? 0 }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addTimeRow(){
    const row = document.createElement('div');
    row.className = 'row g-2 align-items-center mb-2';
    row.innerHTML = '<div class="col"><input type="time" class="form-control" name="available_times[]"></div><div class="col-auto"><button type="button" class="btn btn-outline-danger" onclick="this.closest(\' . ' . 'row' . ' . '\').remove()"><i class="bi bi-trash"></i></button></div>';
    document.getElementById('times-container').appendChild(row);
}
</script>
@endsection


