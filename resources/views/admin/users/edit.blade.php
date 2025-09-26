@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)
@section('page-title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="bi bi-pencil me-2"></i>Edit {{ $user->name }}</h5>
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-secondary">Back</a>
            </div>
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Document</label>
                            <input type="text" class="form-control @error('document') is-invalid @enderror" name="document" value="{{ old('document', $user->document) }}">
                            @error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Birth Date</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}">
                            @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Active</label>
                            <select class="form-select @error('is_active') is-invalid @enderror" name="is_active">
                                <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>No</option>
                            </select>
                            @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city', $user->city) }}">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state', $user->state) }}">
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Zip Code</label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}">
                            @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country', $user->country) }}">
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


