@extends('layouts.master')

@section('title')
    Create Carousel Slide
@endsection

@section('css')
    <!-- Dropzone -->
    <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css" />
    <!-- Color Picker -->
    <link href="{{ URL::asset('build/libs/@simonwep/pickr/themes/classic.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Site Settings
        @endslot
        @slot('title')
            Create Carousel Slide
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Create New Carousel Slide</h4>
                            <p class="card-title-desc">Fill in the slide information below</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('site-settings.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back to Site Settings
                            </a>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="bx bx-error-circle me-2"></i>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('site-settings.carousel.store') }}" method="POST" enctype="multipart/form-data" id="carousel-form">
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Slide Content</h5>

                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                   id="title" name="title" value="{{ old('title') }}"
                                                   placeholder="Enter slide title">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="subtitle" class="form-label">Subtitle</label>
                                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                                   id="subtitle" name="subtitle" value="{{ old('subtitle') }}"
                                                   placeholder="Enter slide subtitle">
                                            @error('subtitle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                      id="description" name="description" rows="4"
                                                      placeholder="Enter slide description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="button_text" class="form-label">Button Text</label>
                                                    <input type="text" class="form-control @error('button_text') is-invalid @enderror"
                                                           id="button_text" name="button_text" value="{{ old('button_text') }}"
                                                           placeholder="Learn More">
                                                    @error('button_text')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="button_url" class="form-label">Button URL</label>
                                                    <input type="url" class="form-control @error('button_url') is-invalid @enderror"
                                                           id="button_url" name="button_url" value="{{ old('button_url') }}"
                                                           placeholder="https://example.com">
                                                    @error('button_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Slide Image -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Slide Image</h5>
                                        <p class="card-title-desc">Upload an image for your carousel slide (recommended: 1920x800px)</p>

                                        <div class="fallback">
                                            <input name="image" type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div id="image-preview" class="mt-3" style="display: none;">
                                            <img id="preview-img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Slide Settings</h5>

                                        <div class="mb-3">
                                            <label for="order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control @error('order') is-invalid @enderror"
                                                   id="order" name="order" value="{{ old('order', 0) }}"
                                                   min="0" placeholder="0">
                                            <div class="form-text">Lower numbers appear first</div>
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="text_color" class="form-label">Text Color</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('text_color') is-invalid @enderror"
                                                       id="text_color" name="text_color" value="{{ old('text_color') }}"
                                                       placeholder="#ffffff">
                                                <button class="btn btn-outline-secondary" type="button" id="text-color-picker">
                                                    <i class="bx bx-palette"></i>
                                                </button>
                                            </div>
                                            @error('text_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="background_overlay" class="form-label">Background Overlay</label>
                                            <input type="text" class="form-control @error('background_overlay') is-invalid @enderror"
                                                   id="background_overlay" name="background_overlay" value="{{ old('background_overlay', 'rgba(0,0,0,0.3)') }}"
                                                   placeholder="rgba(0,0,0,0.3)">
                                            <div class="form-text">CSS background value for text overlay</div>
                                            @error('background_overlay')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                            <div class="form-text">Only active slides will be displayed</div>
                                        </div>

                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bx bx-save me-1"></i> Create Slide
                                            </button>
                                            <a href="{{ route('site-settings.index') }}" class="btn btn-secondary">
                                                <i class="bx bx-x me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Live Preview -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Live Preview</h5>
                                        <div class="slide-preview position-relative">
                                            <div id="preview-slide" class="bg-light d-flex align-items-center justify-content-center rounded"
                                                 style="height: 200px; background-image: url(''); background-size: cover; background-position: center;">
                                                <div id="preview-content" class="text-center text-white p-3 rounded"
                                                     style="background: rgba(0,0,0,0.3); color: #ffffff;">
                                                    <h6 id="preview-title" class="mb-1">Slide Title</h6>
                                                    <p id="preview-subtitle" class="mb-2 small">Slide Subtitle</p>
                                                    <button id="preview-button" class="btn btn-primary btn-sm" style="display: none;">
                                                        Button Text
                                                    </button>
                                                </div>
                                            </div>
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
    <script type="text/javascript">
        $(document).ready(function() {
            // Image preview
            $('input[name="image"]').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-img').attr('src', e.target.result);
                        $('#image-preview').show();
                        $('#preview-slide').css('background-image', 'url(' + e.target.result + ')');
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#image-preview').hide();
                    $('#preview-slide').css('background-image', 'none');
                }
            });

            // Live preview updates
            $('#title').on('input', function() {
                const value = $(this).val() || 'Slide Title';
                $('#preview-title').text(value);
            });

            $('#subtitle').on('input', function() {
                const value = $(this).val() || 'Slide Subtitle';
                $('#preview-subtitle').text(value);
            });

            $('#button_text').on('input', function() {
                const value = $(this).val();
                if (value) {
                    $('#preview-button').text(value).show();
                } else {
                    $('#preview-button').hide();
                }
            });

            $('#text_color').on('input', function() {
                const color = $(this).val() || '#ffffff';
                $('#preview-content').css('color', color);
            });

            $('#background_overlay').on('input', function() {
                const overlay = $(this).val() || 'rgba(0,0,0,0.3)';
                $('#preview-content').css('background', overlay);
            });

            // Simple color picker for text color
            $('#text-color-picker').on('click', function() {
                const colorInput = document.createElement('input');
                colorInput.type = 'color';
                colorInput.value = $('#text_color').val();
                colorInput.onchange = function() {
                    $('#text_color').val(this.value).trigger('input');
                };
                colorInput.click();
            });
        });
    </script>
@endsection
