@extends('layouts.master')

@section('title') Product Details @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Products @endslot
@slot('li_2') <a href="{{ route('products.index') }}">Products</a> @endslot
@slot('title') {{ $product->name }} @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product Details - {{ $product->name }}</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <h5 class="font-size-14 text-muted">Basic Information:</h5>
                            <p class="mb-1"><strong>Name:</strong> {{ $product->name }}</p>
                            <p class="mb-1"><strong>Slug:</strong> <code>{{ $product->slug }}</code></p>
                            <p class="mb-0"><strong>Price:</strong> <span class="text-success font-weight-bold">{{ number_format($product->price, 2) }} СОМ</span></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <h5 class="font-size-14 text-muted">Category & Brand:</h5>
                            <p class="mb-1"><strong>Category:</strong> {{ $product->category ? $product->category->name : 'N/A' }}</p>
                            <p class="mb-1"><strong>Brand:</strong> {{ $product->brand ? $product->brand->name : 'No Brand' }}</p>
                            <p class="mb-0"><strong>Created:</strong> {{ $product->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                @if($product->description)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="font-size-14 text-muted">Description:</h5>
                            <div class="alert alert-info">
                                {!! $product->description !!}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <h5 class="font-size-14 text-muted">Product Images:</h5>
                        @if($product->images->count() > 0)
                            <div class="row">
                                @foreach($product->images as $image)
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card">
                                            <div class="position-relative">
                                                <img src="{{ Storage::url($image->image_path) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Product Image">
                                                @if($image->is_primary)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">Primary</span>
                                                    </div>
                                                @endif
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <button type="button" class="btn btn-sm btn-light" onclick="viewImage('{{ Storage::url($image->image_path) }}')">
                                                        <i class="bx bx-expand"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-image text-muted" style="font-size: 48px;"></i>
                                <p class="text-muted mt-2">No images available for this product</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit Product
                    </a>

                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Products
                    </a>

                    <button type="button" class="btn btn-danger" onclick="deleteProduct({{ $product->id }})">
                        <i class="bx bx-trash"></i> Delete Product
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Statistics</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h5 class="font-size-18 mb-1">{{ $product->images->count() }}</h5>
                            <p class="text-muted mb-0">Images</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h5 class="font-size-18 mb-1">{{ number_format($product->price, 2) }} СОМ</h5>
                            <p class="text-muted mb-0">Price</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="text-center">
                            <h6 class="font-size-14 mb-1">Last Updated</h6>
                            <p class="text-muted mb-0">{{ $product->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image View Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Product Image">
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
function viewImage(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

function deleteProduct(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the product and all its images!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('admin/products') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("products.index") }}';
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong while deleting the product.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
