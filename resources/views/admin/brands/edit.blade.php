@extends('layouts.master')

@section('title')
    Edit Brand
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('brands.index') }}">Brands</a>
        @endslot
        @slot('title')
            Edit Brand
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Brand</h4>
                    <p class="card-title-desc">Update brand information</p>

                    <form action="{{ route('brands.update', $brand->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Enter brand name" value="{{ old('name', $brand->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input id="slug" name="slug" type="text" class="form-control"
                                           placeholder="Auto-generated slug" value="{{ $brand->slug }}" readonly>
                                    <small class="text-muted">Slug is automatically generated from the name</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="5"
                                              placeholder="Enter brand description">{{ old('description', $brand->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Update Brand</button>
                            <a href="{{ route('brands.index') }}" class="btn btn-secondary waves-effect waves-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection