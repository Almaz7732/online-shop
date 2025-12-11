<?php $__env->startSection('title', $about->title . ' - Ident'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Facades\Storage;
?>

<!-- Start Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12">
                <ul class="breadcrumb-nav">
                    <li><a href="<?php echo e(route('shop.index')); ?>"><i class="lni lni-home"></i> Главная</a></li>
                    <li>О нас</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Start About Us Section -->
<section class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="about-content text-center">
                    <h1 class="wow fadeInUp" data-wow-delay=".2s"><?php echo e($about->title); ?></h1>
                    <div class="content mt-4 wow fadeInUp" data-wow-delay=".4s">
                        <?php echo $about->content; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End About Us Section -->

<!-- Start Map Section -->
<section class="map-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Map Tabs -->
                <div class="map-tabs mb-3">
                    <ul class="nav nav-tabs d-flex justify-content-center" id="mapTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="2gis-tab" data-bs-toggle="tab" data-bs-target="#two-gis-map-tab" type="button" role="tab">
                                <i class="lni lni-map-marker me-2"></i>2GIS Map
                            </button>
                        </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mapSettings && $mapSettings->is_active && $mapSettings->google_maps_api_key): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="google-tab" data-bs-toggle="tab" data-bs-target="#google-map-tab" type="button" role="tab">
                                <i class="lni lni-map me-2"></i>Google Maps
                            </button>
                        </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                <!-- Map Content -->
                <div class="tab-content" id="mapTabContent">
                    <!-- 2GIS Map Tab -->
                    <div class="tab-pane active" id="two-gis-map-tab" role="tabpanel">
                        <div id="2gis-container" style="height: 400px; width: 100%;">
                            <script charset="utf-8" src="https://widgets.2gis.com/js/DGWidgetLoader.js"></script>
                            <script charset="utf-8">
                                new DGWidgetLoader({
                                    "width":'100%',
                                    "height": 400,
                                    "borderColor":"#a3a3a3",
                                    "pos": {
                                        "lat":42.84447503993618,
                                        "lon":74.59834814071657,
                                        "zoom":16
                                    },
                                    "opt":{
                                        "city":"bishkek"
                                    },
                                    "org":[
                                        {"id":"70000001067873205"}
                                    ]
                                });
                            </script>
                        </div>
                    </div>

                    <!-- Google Maps Tab -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mapSettings && $mapSettings->is_active && $mapSettings->google_maps_api_key): ?>
                    <div class="tab-pane fade" id="google-map-tab" role="tabpanel">
                        <div id="google-map" style="height: 400px; width: 100%;">
                            <!-- Google Map will be loaded here -->
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Map Section -->

<!-- Start Contact Info -->
<section class="contact-info section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12">
                <!-- Single Info -->
                <div class="single-info">
                    <div class="icon">
                        <i class="lni lni-map-marker"></i>
                    </div>
                    <div class="content">
                        <h4>Address</h4>
                        <p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mapSettings && $mapSettings->company_address): ?>
                                <?php echo e($mapSettings->company_address); ?>

                            <?php elseif(setting('site_address')): ?>
                                <?php echo e(setting('site_address')); ?>

                            <?php else: ?>
                                Our office address will be displayed here
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                </div>
                <!-- End Single Info -->
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <!-- Single Info -->
                <div class="single-info">
                    <div class="icon">
                        <i class="lni lni-phone"></i>
                    </div>
                    <div class="content">
                        <h4>Phone</h4>
                        <p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_phone')): ?>
                                <?php echo e(setting('site_phone')); ?>

                            <?php else: ?>
                                Contact phone number
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                </div>
                <!-- End Single Info -->
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <!-- Single Info -->
                <div class="single-info">
                    <div class="icon">
                        <i class="lni lni-envelope"></i>
                    </div>
                    <div class="content">
                        <h4>Email</h4>
                        <p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_email')): ?>
                                <?php echo e(setting('site_email')); ?>

                            <?php else: ?>
                                contact@company.com
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                </div>
                <!-- End Single Info -->
            </div>
        </div>
    </div>
</section>
<!-- End Contact Info -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .about-us.section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .about-content h1 {
        color: #333;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 30px;
    }

    .about-content .content {
        color: #666;
        font-size: 16px;
        line-height: 1.8;
        max-width: 800px;
        margin: 0 auto;
    }

    .about-content .content p {
        margin-bottom: 20px;
    }

    .about-content .content h2,
    .about-content .content h3,
    .about-content .content h4 {
        color: #333;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .map-section {
        margin: 50px 0;
    }

    .map-placeholder {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
    }

    .map-tabs .nav-tabs {
        border: none;
        background: #f8f9fa;
        border-radius: 10px;
        padding: 5px;
        max-width: 400px;
        margin: 0 auto;
    }

    .map-tabs .nav-link {
        border: none;
        background: transparent;
        color: #666;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin: 0 2px;
    }

    .map-tabs .nav-link:hover {
        background: rgba(1, 103, 243, 0.1);
        color: #0167f3;
    }

    .map-tabs .nav-link.active {
        background: #0167f3;
        color: #fff;
        box-shadow: 0 2px 8px rgba(1, 103, 243, 0.3);
    }

    .tab-content {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .contact-info.section {
        padding: 80px 0;
        background: #fff;
    }

    .single-info {
        text-align: center;
        padding: 30px 20px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .single-info:hover {
        background: #0167f3;
        transform: translateY(-5px);
    }

    .single-info:hover .icon i,
    .single-info:hover .content h4,
    .single-info:hover .content p {
        color: #fff;
    }

    .single-info .icon {
        margin-bottom: 20px;
    }

    .single-info .icon i {
        font-size: 48px;
        color: #0167f3;
        transition: all 0.3s ease;
    }

    .single-info .content h4 {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .single-info .content p {
        color: #666;
        margin: 0;
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .about-content h1 {
            font-size: 2rem;
        }

        .about-content .content {
            font-size: 14px;
        }

        .about-us.section,
        .contact-info.section {
            padding: 50px 0;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Bootstrap JS for tabs functionality -->
<script src="<?php echo e(URL::asset('build/libs/bootstrap/js/bootstrap5-1-3.bundle.min.js')); ?>"></script>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mapSettings && $mapSettings->is_active && $mapSettings->google_maps_api_key): ?>
<script>
    // Google Maps configuration from database
    const mapConfig = {
        apiKey: '<?php echo e($mapSettings->google_maps_api_key); ?>',
        lat: <?php echo e($mapSettings->latitude ?? 55.7558); ?>,
        lng: <?php echo e($mapSettings->longitude ?? 37.6176); ?>,
        zoom: <?php echo e($mapSettings->zoom_level ?? 15); ?>,
        mapType: '<?php echo e($mapSettings->map_style ?? "roadmap"); ?>',
        address: '<?php echo e(addslashes($mapSettings->company_address ?? "Our Office")); ?>'
    };

    // Initialize Google Maps
    function initMap() {
        // Create map instance
        const map = new google.maps.Map(document.getElementById('google-map'), {
            center: { lat: mapConfig.lat, lng: mapConfig.lng },
            zoom: mapConfig.zoom,
            mapTypeId: mapConfig.mapType,
            scrollwheel: false,
            navigationControl: true,
            mapTypeControl: true,
            scaleControl: true,
            styles: getMapStyles(mapConfig.mapType)
        });

        // Create marker
        const marker = new google.maps.Marker({
            position: { lat: mapConfig.lat, lng: mapConfig.lng },
            map: map,
            title: mapConfig.address,
            animation: google.maps.Animation.DROP
        });

        // Create info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; max-width: 300px;">
                    <h5 style="margin: 0 0 10px 0; color: #333;"><?php echo e(setting('site_name') ?? 'Our Company'); ?></h5>
                    <p style="margin: 0; color: #666; line-height: 1.4;">${mapConfig.address}</p>
                </div>
            `
        });

        // Show info window on marker click
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });

        // Auto-open info window after 1 second
        setTimeout(function() {
            infoWindow.open(map, marker);
        }, 1000);
    }

    // Get custom map styles based on map type
    function getMapStyles(mapType) {
        if (mapType === 'roadmap') {
            return [
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{ color: '#e9e9e9' }, { lightness: 17 }]
                },
                {
                    featureType: 'landscape',
                    elementType: 'geometry',
                    stylers: [{ color: '#f5f5f5' }, { lightness: 20 }]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.fill',
                    stylers: [{ color: '#ffffff' }, { lightness: 17 }]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{ color: '#ffffff' }, { lightness: 29 }, { weight: 0.2 }]
                },
                {
                    featureType: 'road.arterial',
                    elementType: 'geometry',
                    stylers: [{ color: '#ffffff' }, { lightness: 18 }]
                },
                {
                    featureType: 'road.local',
                    elementType: 'geometry',
                    stylers: [{ color: '#ffffff' }, { lightness: 16 }]
                },
                {
                    featureType: 'poi',
                    elementType: 'geometry',
                    stylers: [{ color: '#f5f5f5' }, { lightness: 21 }]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{ color: '#dedede' }, { lightness: 21 }]
                },
                {
                    elementType: 'labels.text.stroke',
                    stylers: [{ visibility: 'on' }, { color: '#ffffff' }, { lightness: 16 }]
                },
                {
                    elementType: 'labels.text.fill',
                    stylers: [{ saturation: 36 }, { color: '#333333' }, { lightness: 40 }]
                },
                {
                    elementType: 'labels.icon',
                    stylers: [{ visibility: 'off' }]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{ color: '#f2f2f2' }, { lightness: 19 }]
                },
                {
                    featureType: 'administrative',
                    elementType: 'geometry.fill',
                    stylers: [{ color: '#fefefe' }, { lightness: 20 }]
                },
                {
                    featureType: 'administrative',
                    elementType: 'geometry.stroke',
                    stylers: [{ color: '#fefefe' }, { lightness: 17 }, { weight: 1.2 }]
                }
            ];
        }
        return []; // Return empty array for other map types (satellite, hybrid, terrain)
    }

    // Handle Google Maps API loading errors
    window.gm_authFailure = function() {
        console.error('Google Maps API authentication failed');
        document.getElementById('google-map').innerHTML = `
            <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                <div class="text-center">
                    <i class="lni lni-warning" style="font-size: 48px; color: #dc3545; margin-bottom: 15px;"></i>
                    <h5 class="text-danger">Map Loading Error</h5>
                    <p class="text-muted">Please check Google Maps API configuration</p>
                </div>
            </div>
        `;
    };

    // Load Google Maps API
    function loadGoogleMaps() {
        if (typeof google !== 'undefined' && google.maps) {
            initMap();
            return;
        }

        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${mapConfig.apiKey}&callback=initMap&v=3.exp`;
        script.async = true;
        script.defer = true;
        script.onerror = function() {
            console.error('Failed to load Google Maps API');
            document.getElementById('google-map').innerHTML = `
                <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                    <div class="text-center">
                        <i class="lni lni-warning" style="font-size: 48px; color: #dc3545; margin-bottom: 15px;"></i>
                        <h5 class="text-danger">Map Loading Error</h5>
                        <p class="text-muted">Unable to load Google Maps</p>
                    </div>
                </div>
            `;
        };
        document.head.appendChild(script);
    }

    // Load Google Maps only when tab is activated
    window.googleMapsInitialized = false;

    // Handle tab switching
    document.addEventListener('DOMContentLoaded', function() {
        const googleTab = document.getElementById('google-tab');

        if (googleTab) {
            googleTab.addEventListener('shown.bs.tab', function(event) {
                if (!window.googleMapsInitialized) {
                    loadGoogleMaps();
                    window.googleMapsInitialized = true;
                }
            });
        }
    });
</script>
<?php else: ?>
<script>
    console.log('Google Maps disabled or not configured');
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\shop-laravel\resources\views/clients/shop/about.blade.php ENDPATH**/ ?>