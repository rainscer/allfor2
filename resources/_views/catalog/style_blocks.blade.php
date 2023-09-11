<div class="grid prod-pad">

    {{--@include('catalog.menu')--}}

    @if(isset($products) && count($products))
        @include('catalog.list')
    @else
        <img src="{{ asset('images/no-photo.jpg') }}" class="hidden">
        <div class="product-item grid-item" style="padding: 20px;">{{ trans('product.emptyCategory') }}</div>
    @endif
</div>

@if(isset($products_count))
    <div id="last_page" class="hidden">{!! $products_count  !!}</div>
@endif