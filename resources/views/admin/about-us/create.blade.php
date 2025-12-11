@extends('layouts.master')

@section('title')
    Create About Us Content
@endsection

@section('css')
    <!-- Summer Note -->
    <link href="{{ URL::asset('build/libs/summernote/summernote-bs5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            About Us
        @endslot
        @slot('title')
            Create About Us Content
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Create New About Us Content</h4>
                            <p class="card-title-desc">Fill in the content information below</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.about-us.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back to About Us
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.about-us.store') }}" method="POST" id="about-us-form">
                        @csrf

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Content Information</h5>

                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                   id="title" name="title" value="{{ old('title') }}"
                                                   placeholder="Enter about us title">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('content') is-invalid @enderror"
                                                      id="content" name="content" rows="15">{{ old('content') }}</textarea>
                                            @error('content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Settings</h5>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="is_active" name="is_active" {{ old('is_active') ? 'checked' : 'checked' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active Status
                                                </label>
                                            </div>
                                            <small class="text-muted">Enable this content to be displayed on the website</small>
                                        </div>

                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bx bx-check me-1"></i> Create Content
                                            </button>
                                        </div>

                                        <div class="mb-3">
                                            <button type="button" class="btn btn-secondary w-100" onclick="window.history.back()">
                                                <i class="bx bx-x me-1"></i> Cancel
                                            </button>
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
    <!-- Summer Note -->
    <script src="{{ URL::asset('build/libs/summernote/summernote-bs5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('#content').summernote({
                height: 300,
                placeholder: 'Write your about us content here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            // Form validation
            $('#about-us-form').submit(function(e) {
                var title = $('#title').val().trim();
                var content = $('#content').summernote('code');

                if (title === '') {
                    e.preventDefault();
                    alert('Please enter a title');
                    $('#title').focus();
                    return false;
                }

                if (content === '' || content === '<p><br></p>') {
                    e.preventDefault();
                    alert('Please enter content');
                    $('#content').summernote('focus');
                    return false;
                }
            });
        });
    </script>
@endsection