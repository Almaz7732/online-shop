@extends('layouts.master')

@section('title')
    Create Product
@endsection

@section('css')
    <!-- Select2 -->
    <link href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Dropzone -->
    <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css" />
    <!-- Summer Note -->
    <link href="{{ URL::asset('build/libs/summernote/summernote-bs5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Products
        @endslot
        @slot('title')
            Create Product
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Create New Product</h4>
                            <p class="card-title-desc">Fill in the product information below</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back to Products
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Basic Information</h5>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name') }}"
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
                                                       id="price" name="price" value="{{ old('price') }}"
                                                       step="0.01" min="0" placeholder="0.00" required>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Images -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Product Images</h5>
                                        <p class="card-title-desc">Upload multiple images for your product. The first uploaded image will be set as primary.</p>

                                        <div class="dropzone" id="product-images-dropzone">
                                            <div class="fallback">
                                                <input name="images[]" type="file" multiple="multiple" accept="image/*">
                                            </div>
                                            <div class="dz-message needsclick">
                                                <div class="mb-3">
                                                    <i class="display-4 text-muted bx bx-image-add"></i>
                                                </div>
                                                <h4>Drop product images here or click to upload.</h4>
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
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                    @if($category->children->count() > 0)
                                                        @foreach($category->children as $child)
                                                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
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
                                                <i class="bx bx-save me-1"></i> Create Product
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
                                                <img id="preview-image" src="https://via.placeholder.com/300x300?text=No+Image"
                                                     class="img-fluid rounded" alt="Product Preview"
                                                     style="width: 100%; height: 200px; object-fit: cover;">
                                            </div>
                                            <h6 id="preview-name" class="text-muted">Product Name</h6>
                                            <h5 id="preview-price" class="text-primary">0.00 СОМ</h5>
                                            <p id="preview-category" class="text-muted small">Category: Not selected</p>
                                            <p id="preview-brand" class="text-muted small">Brand: No Brand</p>
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
                dictDefaultMessage: "Drop product images here or click to upload.",

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
                        } else {
                            resetImagePreview();
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

            function resetImagePreview() {
                $('#preview-image').attr('src', 'https://via.placeholder.com/300x300?text=No+Image');
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
    </script>
@endsection
