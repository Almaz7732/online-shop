@extends('layouts.master')

@section('title')
    Edit Product
@endsection

@section('css')
    <!-- Select2 -->
    <link href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Dropzone -->
    <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert -->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Summer Note -->
    <link href="{{ URL::asset('build/libs/summernote/summernote-bs5.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Products
        @endslot
        @slot('title')
            Edit Product
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Edit Product</h4>
                            <p class="card-title-desc">Update product information below</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back to Products
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="product-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Basic Information</h5>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name', $product->name) }}"
                                                   placeholder="Enter product name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">СОМ</span>
                                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                                       id="price" name="price" value="{{ old('price', $product->price) }}"
                                                       step="0.01" min="0" placeholder="0.00" required>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Existing Product Images -->
                                @if($product->images->count() > 0)
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Current Product Images</h5>
                                        <div class="row" id="existing-images">
                                            @foreach($product->images as $image)
                                            <div class="col-md-3 col-sm-6 mb-3" data-image-id="{{ $image->id }}">
                                                <div class="card h-100 position-relative">
                                                    <img src="{{ Storage::url($image->image_path) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Product Image">
                                                    <div class="card-body p-2">
                                                        @if($image->is_primary)
                                                            <span class="badge bg-primary mb-1">Primary Image</span>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="setPrimaryImage({{ $image->id }})">
                                                                Set as Primary
                                                            </button>
                                                        @endif
                                                        <br>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteImage({{ $image->id }})">
                                                            <i class="bx bx-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Add New Images -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Add New Images</h5>
                                        <p class="card-title-desc">Upload additional images for your product.</p>

                                        <div class="dropzone" id="product-images-dropzone">
                                            <div class="fallback">
                                                <input name="images[]" type="file" multiple="multiple" accept="image/*">
                                            </div>
                                            <div class="dz-message needsclick">
                                                <div class="mb-3">
                                                    <i class="display-4 text-muted bx bx-image-add"></i>
                                                </div>
                                                <h4>Drop new images here or click to upload.</h4>
                                                <p class="text-muted">Only image files are allowed (JPG, PNG, GIF)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Product Details</h5>

                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('category_id') is-invalid @enderror"
                                                    id="category_id" name="category_id" required>
                                                <option value="">Select a category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                    @if($category->children->count() > 0)
                                                        @foreach($category->children as $child)
                                                            <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                                                &nbsp;&nbsp;── {{ $child->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="brand_id" class="form-label">Brand</label>
                                            <select class="form-select select2 @error('brand_id') is-invalid @enderror"
                                                    id="brand_id" name="brand_id">
                                                <option value="">No Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bx bx-save me-1"></i> Update Product
                                            </button>
                                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                                <i class="bx bx-x me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Preview -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Preview</h5>
                                        <div class="product-preview">
                                            <div class="product-img-preview mb-3">
                                                @if($product->primaryImage)
                                                    <img id="preview-image" src="{{ Storage::url($product->primaryImage->image_path) }}"
                                                         class="img-fluid rounded" alt="Product Preview"
                                                         style="width: 100%; height: 200px; object-fit: cover;">
                                                @else
                                                    <img id="preview-image" src="https://via.placeholder.com/300x300?text=No+Image"
                                                         class="img-fluid rounded" alt="Product Preview"
                                                         style="width: 100%; height: 200px; object-fit: cover;">
                                                @endif
                                            </div>
                                            <h6 id="preview-name" class="text-muted">{{ $product->name }}</h6>
                                            <h5 id="preview-price" class="text-primary">{{ number_format($product->price, 2) }} СОМ</h5>
                                            <p id="preview-category" class="text-muted small">Category: {{ $product->category ? $product->category->name : 'Not selected' }}</p>
                                            <p id="preview-brand" class="text-muted small">Brand: {{ $product->brand ? $product->brand->name : 'No Brand' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <!-- Dropzone -->
    <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
    <!-- Sweet Alert -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Summer Note -->
    <script src="{{ URL::asset('build/libs/summernote/summernote-bs5.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
                width: '100%'
            });

            // Initialize SummerNote
            $('#description').summernote({
                placeholder: 'Hello stand alone ui',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link',]],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });


            // Initialize Dropzone
            Dropzone.autoDiscover = false;
            const productDropzone = new Dropzone("#product-images-dropzone", {
                url: "#", // We'll handle upload on form submit
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                maxFilesize: 2, // MB
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                dictRemoveFile: "Remove",
                dictDefaultMessage: "Drop new images here or click to upload.",

                init: function() {
                    const dropzone = this;

                    // Handle form submission
                    $("#product-form").on("submit", function(e) {
                        if (dropzone.getQueuedFiles().length > 0) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Create FormData with form fields
                            const formData = new FormData(this);

                            // Add dropzone files
                            dropzone.getQueuedFiles().forEach(function(file) {
                                formData.append('images[]', file);
                            });

                            // Submit via Ajax
                            $.ajax({
                                url: $(this).attr('action'),
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    window.location.href = "{{ route('products.index') }}";
                                },
                                error: function(xhr) {
                                    if (xhr.status === 422) {
                                        const errors = xhr.responseJSON.errors;
                                        // Handle validation errors
                                        Object.keys(errors).forEach(function(key) {
                                            const input = $('[name="' + key + '"]');
                                            input.addClass('is-invalid');
                                            input.closest('.mb-3').find('.invalid-feedback').remove();
                                            input.after('<div class="invalid-feedback">' + errors[key][0] + '</div>');
                                        });
                                    }
                                }
                            });
                        }
                    });

                    // Update preview when files are added
                    this.on("addedfile", function(file) {
                        if (this.files[0] === file) {
                            updateImagePreview(file);
                        }
                    });

                    this.on("removedfile", function(file) {
                        if (this.files.length > 0) {
                            updateImagePreview(this.files[0]);
                        }
                    });
                }
            });

            // Update preview functions
            function updateImagePreview(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }

            // Live preview updates
            $('#name').on('input', function() {
                const value = $(this).val() || 'Product Name';
                $('#preview-name').text(value);
            });

            $('#price').on('input', function() {
                const value = parseFloat($(this).val()) || 0;
                $('#preview-price').text(value.toFixed(2) + ' СОМ');
            });

            $('#category_id').on('change', function() {
                const text = $(this).find('option:selected').text() || 'Not selected';
                $('#preview-category').text('Category: ' + text);
            });

            $('#brand_id').on('change', function() {
                const text = $(this).find('option:selected').text() || 'No Brand';
                $('#preview-brand').text('Brand: ' + text);
            });
        });

        // Delete existing image
        function deleteImage(imageId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the image permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/product-images') }}/" + imageId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('[data-image-id="' + imageId + '"]').fadeOut(300, function() {
                                $(this).remove();
                                // If no images left, show placeholder
                                if ($('#existing-images .col-md-3').length === 0) {
                                    $('#preview-image').attr('src', 'https://via.placeholder.com/300x300?text=No+Image');
                                }
                            });
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Image has been deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong while deleting the image.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        // Set primary image
        function setPrimaryImage(imageId) {
            $.ajax({
                url: "{{ url('admin/product-images') }}/" + imageId + "/set-primary",
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Remove primary badge from all images
                    $('#existing-images .badge').remove();
                    $('#existing-images button:contains("Set as Primary")').show();

                    // Add primary badge to selected image and hide button
                    const imageCard = $('[data-image-id="' + imageId + '"]');
                    imageCard.find('.card-body').prepend('<span class="badge bg-primary mb-1">Primary Image</span>');
                    imageCard.find('button:contains("Set as Primary")').hide();

                    // Update preview image
                    const newPrimaryImg = imageCard.find('img').attr('src');
                    $('#preview-image').attr('src', newPrimaryImg);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Primary image has been updated.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong while setting primary image.',
                        timer: 3000,
                        showConfirmButton: false
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
