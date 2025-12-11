@extends('layouts.master')

@section('title')
    Создать SEO настройку
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.seo.index') }}">SEO настройки</a>
        @endslot
        @slot('title')
            Создать SEO настройку
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Новая SEO настройка</h4>

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

                    <form action="{{ route('admin.seo.store') }}" method="POST">
                        @csrf
                        @include('admin.seo._form')

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i> Создать SEO настройку
                                    </button>
                                    <a href="{{ route('admin.seo.index') }}" class="btn btn-secondary">
                                        <i class="bx bx-arrow-back me-1"></i> Отмена
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection