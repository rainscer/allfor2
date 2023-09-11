{{-- This is for load product page by get method --}}
@if(isset($product_open_modal))
    <input type="text" id="site-title" class="hidden" value="{{ trans('home.title') }}">
    <input type="text" id="site-url" class="hidden" value="{{ url('/') }}">
    <script>
        $(document).ready(function () {
            $('#myModal').data('href', '{{ route('product.url',[$product_open_modal->upi_id, $product_open_modal->slug]) }}');
            reloadModal($('#myModal'));
        });
    </script>
@else
    <input type="text" id="site-title" class="hidden" value="{{ isset($title) ? $title : trans('home.title') }}">
    <input type="text" id="site-url" class="hidden" value="{{ Request::url() }}">
@endif