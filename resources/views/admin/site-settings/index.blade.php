@extends('layouts.master')

@section('title')
    Настройки сайта
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert -->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Настройки сайта
        @endslot
        @slot('title')
            Настройки сайта
        @endslot
    @endcomponent

    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Общие настройки</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="bx bx-error-circle me-2"></i>Пожалуйста, исправьте следующие ошибки:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('site-settings.update') }}" method="POST" id="general-settings-form">
                        @csrf

                        <div class="row">
                            <div class="col">
                                <p class="card-title-desc">Manage your site's basic information and contact details</p>

                                <div class="mb-3">
                                    <label for="site_phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('site_phone') is-invalid @enderror"
                                           id="site_phone" name="site_phone"
                                           value="{{ old('site_phone', $generalSettings['site_phone']->value ?? '') }}"
                                           placeholder="Enter phone number">
                                    @error('site_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="site_email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('site_email') is-invalid @enderror"
                                           id="site_email" name="site_email"
                                           value="{{ old('site_email', $generalSettings['site_email']->value ?? '') }}"
                                           placeholder="Enter email address">
                                    @error('site_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="site_address" class="form-label">Address</label>
                                    <textarea class="form-control @error('site_address') is-invalid @enderror"
                                              id="site_address" name="site_address" rows="3"
                                              placeholder="Enter physical address">{{ old('site_address', $generalSettings['site_address']->value ?? '') }}</textarea>
                                    @error('site_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <p class="card-title-desc">Social Media Links</p>

                                <div class="mb-3">
                                    <label for="site_instagram" class="form-label">Instagram</label>
                                    <input type="url" class="form-control @error('site_instagram') is-invalid @enderror"
                                           id="site_instagram" name="site_instagram"
                                           value="{{ old('site_instagram', $generalSettings['site_instagram']->value ?? '') }}"
                                           placeholder="https://instagram.com/yourprofile">
                                    @error('site_instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="site_facebook" class="form-label">Facebook</label>
                                    <input type="url" class="form-control @error('site_facebook') is-invalid @enderror"
                                           id="site_facebook" name="site_facebook"
                                           value="{{ old('site_facebook', $generalSettings['site_facebook']->value ?? '') }}"
                                           placeholder="https://facebook.com/yourpage">
                                    @error('site_facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="site_twitter" class="form-label">Twitter</label>
                                    <input type="url" class="form-control @error('site_twitter') is-invalid @enderror"
                                           id="site_twitter" name="site_twitter"
                                           value="{{ old('site_twitter', $generalSettings['site_twitter']->value ?? '') }}"
                                           placeholder="https://twitter.com/yourprofile">
                                    @error('site_twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="site_youtube" class="form-label">YouTube</label>
                                    <input type="url" class="form-control @error('site_youtube') is-invalid @enderror"
                                           id="site_youtube" name="site_youtube"
                                           value="{{ old('site_youtube', $generalSettings['site_youtube']->value ?? '') }}"
                                           placeholder="https://youtube.com/yourchannel">
                                    @error('site_youtube')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i> Save Settings
                                    </button>
                                </div>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>

        <!-- Carousel Preview -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Carousel Preview</h4>
                    <p class="card-title-desc">Preview of your active carousel slides</p>

                    @if($carouselSlides->count() > 0)
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($carouselSlides as $index => $slide)
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach($carouselSlides as $index => $slide)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="position-relative">
                                            @if($slide->image_path)
                                                <img src="{{ Storage::url($slide->image_path) }}" class="d-block w-100"
                                                     style="height: 300px; object-fit: cover;" alt="{{ $slide->title }}">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                                    <i class="bx bx-image text-muted" style="font-size: 4rem;"></i>
                                                </div>
                                            @endif
                                            @if($slide->title || $slide->subtitle || $slide->description)
                                                <div class="carousel-caption d-none d-md-block"
                                                     style="background: {{ $slide->background_overlay }}; color: {{ $slide->text_color }}; border-radius: 8px; padding: 15px;">
                                                    @if($slide->title)
                                                        <h5>{{ $slide->title }}</h5>
                                                    @endif
                                                    @if($slide->subtitle)
                                                        <p class="mb-0">{{ $slide->subtitle }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-image text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">No carousel slides</h5>
                            <p class="text-muted">Create your first carousel slide to see preview here</p>
                            <a href="{{ route('site-settings.carousel.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Add First Slide
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Carousel Management</h4>
                            <p class="card-title-desc">Manage carousel slides for your homepage</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('site-settings.carousel.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Add New Slide
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="carousel-table" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Sweet Alert -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#carousel-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('site-settings.carousel.data') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
                    {data: 'image', name: 'image', orderable: false, searchable: false, width: '10%'},
                    {data: 'title', name: 'title', width: '25%'},
                    {data: 'subtitle', name: 'subtitle', width: '30%'},
                    {data: 'order', name: 'order', width: '8%'},
                    {data: 'status', name: 'status', orderable: false, searchable: false, width: '10%'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, width: '12%'},
                ],
                order: [[4, 'asc']],
                pageLength: 25,
                responsive: true,
                language: {
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                    emptyTable: 'No carousel slides found'
                }
            });
        });

        function deleteSlide(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the carousel slide and its image!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/site-settings/carousel') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                timer: 3000,
                                showConfirmButton: false
                            });
                            $('#carousel-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong while deleting the slide.',
                                timer: 3000,
                                showConfirmButton: false
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
