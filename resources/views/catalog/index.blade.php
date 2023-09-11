@extends('layout.app')

@if(! empty($banner))
    @section('bannder')
        @include('layout.banner')
    @endsection
@endif

@section('content')

    @if(
        !Cookie::has('adult')
        && isset($catalog_active)
        && collect(app('Catalog')->getAllParents($catalog_active->slug))->contains('slug', \App\Models\CatalogProduct::CATEGORY_18_SLUG)
    )
        @include('catalog.adultBlock')
    @endif

    @include('catalog.style_blocks')

@endsection