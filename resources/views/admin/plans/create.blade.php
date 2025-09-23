@extends('layouts.admin')

@section('title', 'Create Plan')
@section('page-title', 'Create New Plan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Create New Plan
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

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

                <form method="POST" action="{{ route('admin.plans.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Plan Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sale_price" class="form-label">Sale Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control @error('sale_price') is-invalid @enderror" 
                                                   id="sale_price" 
                                                   name="sale_price" 
                                                   value="{{ old('sale_price') }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                        </div>
                                        @error('sale_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cost_price" class="form-label">Cost Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control @error('cost_price') is-invalid @enderror" 
                                                   id="cost_price" 
                                                   name="cost_price" 
                                                   value="{{ old('cost_price') }}" 
                                                   step="0.01" 
                                                   min="0">
                                        </div>
                                        @error('cost_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="commission_percentage" class="form-label">Commission %</label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control @error('commission_percentage') is-invalid @enderror" 
                                                   id="commission_percentage" 
                                                   name="commission_percentage" 
                                                   value="{{ old('commission_percentage') }}" 
                                                   min="0" 
                                                   max="100">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('commission_percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="commission_fixed" class="form-label">Fixed Commission</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control @error('commission_fixed') is-invalid @enderror" 
                                                   id="commission_fixed" 
                                                   name="commission_fixed" 
                                                   value="{{ old('commission_fixed') }}" 
                                                   step="0.01" 
                                                   min="0">
                                        </div>
                                        @error('commission_fixed')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Courses Selection -->
                            <div class="mb-3">
                                <label class="form-label">Include Courses</label>
                                <div class="row">
                                    @forelse($courses as $course)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="course_{{ $course->id }}" 
                                                       name="courses[]" 
                                                       value="{{ $course->id }}"
                                                       {{ in_array($course->id, old('courses', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="course_{{ $course->id }}">
                                                    {{ $course->title }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                No courses available. <a href="{{ route('admin.courses.create') }}">Create a course first</a>.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image" class="form-label">Plan Image</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                <div class="form-text">Upload an image for the plan (max 2MB)</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Plan Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="physical" {{ old('type') === 'physical' ? 'selected' : '' }}>Physical Product</option>
                                    <option value="digital" {{ old('type') === 'digital' ? 'selected' : '' }}>Digital Product</option>
                                    <option value="service" {{ old('type') === 'service' ? 'selected' : '' }}>Service</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Plan
                                    </label>
                                </div>
                                <div class="form-text">Make this plan available for sale</div>
                            </div>

                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', 0) }}" 
                                       min="0">
                                <div class="form-text">Lower numbers appear first</div>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Products Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title mb-0">
                                            <i class="bi bi-box me-2"></i>Plan Products
                                        </h6>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addProduct()">
                                            <i class="bi bi-plus me-1"></i>Add Product
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="products-container">
                                        <!-- Products will be added here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Plans
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Create Plan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let productCount = 0;

function addProduct() {
    const container = document.getElementById('products-container');
    const productHtml = `
        <div class="product-item border rounded p-3 mb-3" data-product-index="${productCount}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Product ${productCount + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProduct(${productCount})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="products[${productCount}][name]" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Download URL</label>
                        <input type="url" class="form-control" name="products[${productCount}][download_url]">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="products[${productCount}][description]" rows="2"></textarea>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', productHtml);
    productCount++;
}

function removeProduct(index) {
    const productItem = document.querySelector(`[data-product-index="${index}"]`);
    if (productItem) {
        productItem.remove();
    }
}

// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('image-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'image-preview';
                preview.className = 'img-thumbnail mt-2';
                preview.style.maxWidth = '200px';
                preview.style.maxHeight = '200px';
                e.target.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Commission calculation
document.getElementById('sale_price').addEventListener('input', function() {
    const salePrice = parseFloat(this.value) || 0;
    const commissionPercentage = parseFloat(document.getElementById('commission_percentage').value) || 0;
    const commissionFixed = parseFloat(document.getElementById('commission_fixed').value) || 0;
    
    let calculatedCommission = 0;
    if (commissionPercentage > 0) {
        calculatedCommission = salePrice * (commissionPercentage / 100);
    } else if (commissionFixed > 0) {
        calculatedCommission = commissionFixed;
    }
    
    // You can display the calculated commission somewhere if needed
    console.log('Calculated commission:', calculatedCommission);
});
</script>
@endsection