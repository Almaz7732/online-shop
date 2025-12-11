@extends('clients.layouts.app')
@push('styles')
    <link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('title', "{$product->name} - " . config('app.name', 'Example'))

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!-- Start Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="breadcrumbs-content">
                    <h1 class="page-title">{{ $product->name }}</h1>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <ul class="breadcrumb-nav">
                    <li><a href="{{ route('shop.index') }}"><i class="lni lni-home"></i> Главная</a></li>
                    <li><a href="{{ route('shop.products') }}">Магазин</a></li>
                    @if($product->category)
                        <li><a href="{{ route('shop.products.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li>{{ $product->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Start Item Details -->
<section class="item-details section">
    <div class="container">
        <div class="top-area">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-images">
                        <main id="gallery">
                            <div class="main-img position-relative">
                                @if($product->images->count() > 0)
                                    <img src="{{ Storage::url($product->images->first()->image_path) }}" id="current" alt="{{ $product->name }}">
                                @else
                                    <img src="{{URL::asset('build/images/default-image.png')}}" id="current" alt="{{ $product->name }}">
                                @endif
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <button type="button" class="btn btn-sm btn-light" onclick="viewImage()">
                                            <i class="bx bx-expand"></i>
                                        </button>
                                    </div>
                            </div>
                            <div class="images">
                                @if($product->images->count() > 0)
                                    @foreach($product->images as $image)
                                        <img src="{{ Storage::url($image->image_path) }}" class="img" alt="{{ $product->name }} - Image {{ $loop->iteration }}">
                                    @endforeach
                                @endif
                            </div>
                        </main>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-info">
                        <h2 class="title">{{ $product->name }}</h2>
                        <p class="category"><i class="lni lni-tag"></i>
                            @if($product->category)
                                {{ $product->category->name }}
                                @if($product->category->parent)
                                    <a href="javascript:void(0)">{{ $product->category->parent->name }}</a>
                                @endif
                            @endif
                            @if($product->brand)
                                <span class="ms-3"><i class="lni lni-bookmark"></i> {{ $product->brand->name }}</span>
                            @endif
                        </p>
                        <h3 class="price">{{ number_format($product->price, 2) }} СОМ</h3>
                        <p class="info-text">{!! $product->description ? : 'Описание товара скоро появится.' !!}</p>
                        <div class="bottom-content">
                            <div class="row align-items-end">
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="button cart-button">
                                        <button class="btn add-to-cart" data-product-id="{{ $product->id }}" style="width: 100%;">Добавить в корзину</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="wishlist-btn" style="position: static">
                                        <a href="javascript:void(0)" class="wishlist-toggle" data-wishlist-id="{{ $product->id }}" title="Добавить в избранное">
                                            <i class="lni lni-heart"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-details-info">
            <div class="single-block">
                <div class="row">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Item Details -->

<!-- Review Modal -->
<div class="modal fade review-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Оставить отзыв</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="review-name">Ваше имя</label>
                            <input class="form-control" type="text" id="review-name" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="review-email">Ваш Email</label>
                            <input class="form-control" type="email" id="review-email" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="review-subject">Тема</label>
                            <input class="form-control" type="text" id="review-subject" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="review-rating">Рейтинг</label>
                            <select class="form-control" id="review-rating">
                                <option>5 звезд</option>
                                <option>4 звезды</option>
                                <option>3 звезды</option>
                                <option>2 звезды</option>
                                <option>1 звезда</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="review-message">Отзыв</label>
                    <textarea class="form-control" id="review-message" rows="8" required></textarea>
                </div>
            </div>
            <div class="modal-footer button">
                <button type="button" class="btn" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn">Отправить отзыв</button>
            </div>
        </div>
    </div>
</div>
<!-- End Review Modal -->

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

@section('scripts')
<script>
    function viewImage() {
        document.getElementById('modalImage').src = fullImg.src;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    // Product Image Gallery
    const imgs = document.querySelectorAll('.img');
    const fullImg = document.querySelector('#current');

    imgs.forEach((img) => {
        img.addEventListener('click', (e) => {
            fullImg.src = e.target.src;
        });
    });
</script>
@endsection
