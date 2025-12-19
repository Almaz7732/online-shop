@extends('layouts.master')

@section('title')
    Настройки карты
@endsection

@section('css')
    <!-- Sweet Alert -->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Настройки сайта
        @endslot
        @slot('title')
            Настройки карты
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Настройка Google Карт</h4>
                            <p class="card-title-desc">Настройте параметры Google Карт для страницы "О нас"</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.map-settings.update') }}" method="POST" id="map-settings-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Google Maps API Configuration</h5>

                                        <div class="mb-3">
                                            <label for="google_maps_api_key" class="form-label">Google Maps API Key</label>
                                            <input type="text" class="form-control @error('google_maps_api_key') is-invalid @enderror"
                                                   id="google_maps_api_key" name="google_maps_api_key"
                                                   value="{{ old('google_maps_api_key', $mapSettings->google_maps_api_key) }}"
                                                   placeholder="Enter your Google Maps API key">
                                            @error('google_maps_api_key')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Get your API key from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Google Cloud Console</a></small>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="latitude" class="form-label">Latitude</label>
                                                    <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror"
                                                           id="latitude" name="latitude"
                                                           value="{{ old('latitude', $mapSettings->latitude) }}"
                                                           placeholder="55.7558">
                                                    @error('latitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="longitude" class="form-label">Longitude</label>
                                                    <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror"
                                                           id="longitude" name="longitude"
                                                           value="{{ old('longitude', $mapSettings->longitude) }}"
                                                           placeholder="37.6176">
                                                    @error('longitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="zoom_level" class="form-label">Zoom Level</label>
                                                    <input type="number" min="1" max="20" class="form-control @error('zoom_level') is-invalid @enderror"
                                                           id="zoom_level" name="zoom_level"
                                                           value="{{ old('zoom_level', $mapSettings->zoom_level ?? 15) }}"
                                                           placeholder="15">
                                                    @error('zoom_level')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">1 = World view, 20 = Street level</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="map_style" class="form-label">Map Style</label>
                                                    <select class="form-select @error('map_style') is-invalid @enderror" id="map_style" name="map_style">
                                                        @foreach(\App\Models\MapSetting::getMapStyles() as $value => $label)
                                                            <option value="{{ $value }}" {{ old('map_style', $mapSettings->map_style) == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('map_style')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_address" class="form-label">Company Address</label>
                                            <textarea class="form-control @error('company_address') is-invalid @enderror"
                                                      id="company_address" name="company_address" rows="3"
                                                      placeholder="Enter your company address">{{ old('company_address', $mapSettings->company_address) }}</textarea>
                                            @error('company_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">This address will be displayed on the map marker</small>
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
                                                       id="is_active" name="is_active" {{ old('is_active', $mapSettings->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Enable Map
                                                </label>
                                            </div>
                                            <small class="text-muted">Enable Google Maps on the About page</small>
                                        </div>

                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bx bx-check me-1"></i> Save Settings
                                            </button>
                                        </div>

                                        <div class="border-top pt-3">
                                            <h6 class="text-muted">Map Preview</h6>
                                            <div id="map-preview" class="bg-light border rounded" style="height: 200px; position: relative;">
                                                <div id="map-preview-content" style="height: 100%; width: 100%;"></div>
                                                <div id="map-preview-placeholder" class="d-flex align-items-center justify-content-center h-100"
                                                     style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(248, 249, 250, 0.9);">
                                                    <span class="text-muted">
                                                        <i class="bx bx-map me-2"></i>
                                                        Preview will show when API key is configured
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="text-muted mt-2 d-block">Preview updates automatically when you change settings</small>
                                        </div>

                                        <div class="border-top pt-3 mt-3">
                                            <h6 class="text-muted">Quick Coordinates</h6>
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCoordinates(55.7558, 37.6176)">Moscow</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCoordinates(40.7128, -74.0060)">New York</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCoordinates(51.5074, -0.1278)">London</button>
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
    <!-- Sweet Alert -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        let previewMap = null;
        let previewMarker = null;
        let googleMapsLoaded = false;

        // Set coordinates helper function
        function setCoordinates(lat, lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            updateMapPreview();
        }

        // Load Google Maps API for preview
        function loadGoogleMapsForPreview() {
            const apiKey = document.getElementById('google_maps_api_key').value.trim();

            if (!apiKey || googleMapsLoaded) {
                return;
            }

            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initMapPreview`;
            script.async = true;
            script.defer = true;
            script.onerror = function() {
                showPreviewError('Failed to load Google Maps API');
            };

            window.gm_authFailure = function() {
                showPreviewError('Google Maps API authentication failed');
            };

            document.head.appendChild(script);
        }

        // Initialize map preview
        window.initMapPreview = function() {
            googleMapsLoaded = true;
            updateMapPreview();
        };

        // Update map preview
        function updateMapPreview() {
            const apiKey = document.getElementById('google_maps_api_key').value.trim();
            const lat = parseFloat(document.getElementById('latitude').value) || 55.7558;
            const lng = parseFloat(document.getElementById('longitude').value) || 37.6176;
            const zoom = parseInt(document.getElementById('zoom_level').value) || 15;
            const mapType = document.getElementById('map_style').value || 'roadmap';
            const address = document.getElementById('company_address').value || 'Our Office';

            if (!apiKey) {
                showPreviewPlaceholder();
                return;
            }

            if (!googleMapsLoaded) {
                loadGoogleMapsForPreview();
                return;
            }

            if (typeof google === 'undefined' || !google.maps) {
                showPreviewError('Google Maps not loaded');
                return;
            }

            hidePreviewPlaceholder();

            try {
                // Create or update map
                if (!previewMap) {
                    previewMap = new google.maps.Map(document.getElementById('map-preview-content'), {
                        center: { lat: lat, lng: lng },
                        zoom: zoom,
                        mapTypeId: mapType,
                        scrollwheel: false,
                        disableDefaultUI: true,
                        zoomControl: true,
                        mapTypeControl: false
                    });
                } else {
                    previewMap.setCenter({ lat: lat, lng: lng });
                    previewMap.setZoom(zoom);
                    previewMap.setMapTypeId(mapType);
                }

                // Create or update marker
                if (previewMarker) {
                    previewMarker.setMap(null);
                }

                previewMarker = new google.maps.Marker({
                    position: { lat: lat, lng: lng },
                    map: previewMap,
                    title: address,
                    animation: google.maps.Animation.DROP
                });

                // Create info window
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 5px; max-width: 200px;">
                            <h6 style="margin: 0 0 5px 0; color: #333; font-size: 14px;">Company Location</h6>
                            <p style="margin: 0; color: #666; font-size: 12px; line-height: 1.3;">${address}</p>
                        </div>
                    `
                });

                previewMarker.addListener('click', function() {
                    infoWindow.open(previewMap, previewMarker);
                });

            } catch (error) {
                console.error('Map preview error:', error);
                showPreviewError('Error creating map preview');
            }
        }

        // Show preview placeholder
        function showPreviewPlaceholder() {
            document.getElementById('map-preview-placeholder').style.display = 'flex';
        }

        // Hide preview placeholder
        function hidePreviewPlaceholder() {
            document.getElementById('map-preview-placeholder').style.display = 'none';
        }

        // Show preview error
        function showPreviewError(message) {
            const placeholder = document.getElementById('map-preview-placeholder');
            placeholder.style.display = 'flex';
            placeholder.innerHTML = `
                <span class="text-danger">
                    <i class="bx bx-error me-2"></i>
                    ${message}
                </span>
            `;
        }

        // Show success message if exists
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        // Form validation
        $('#map-settings-form').submit(function(e) {
            var apiKey = $('#google_maps_api_key').val().trim();
            var lat = $('#latitude').val();
            var lng = $('#longitude').val();

            if (apiKey && (!lat || !lng)) {
                e.preventDefault();
                Swal.fire({
                    title: 'Missing Coordinates',
                    text: 'Please provide both latitude and longitude when using API key.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });

        // Event listeners for real-time preview updates
        document.addEventListener('DOMContentLoaded', function() {
            // Update preview when API key is entered
            $('#google_maps_api_key').on('input', function() {
                const apiKey = this.value.trim();
                if (apiKey.length > 20) { // Basic validation for API key length
                    setTimeout(updateMapPreview, 500); // Debounce
                } else {
                    showPreviewPlaceholder();
                }
            });

            // Update preview when coordinates change
            $('#latitude, #longitude, #zoom_level').on('input change', function() {
                if (googleMapsLoaded) {
                    setTimeout(updateMapPreview, 300); // Debounce
                }
            });

            // Update preview when map style changes
            $('#map_style').on('change', function() {
                if (googleMapsLoaded) {
                    updateMapPreview();
                }
            });

            // Update preview when address changes
            $('#company_address').on('input', function() {
                if (googleMapsLoaded && previewMarker) {
                    const address = this.value || 'Our Office';
                    previewMarker.setTitle(address);
                }
            });

            // Initial preview setup
            const apiKey = document.getElementById('google_maps_api_key').value.trim();
            if (apiKey) {
                loadGoogleMapsForPreview();
            }
        });
    </script>
@endsection