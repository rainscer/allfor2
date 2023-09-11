<div class="prod-pad">
   {{-- <div class="dopmenu list-style-menu col-md-2">

        @include('catalog.menu')

    </div>--}}
    <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 col-sm-12 col-xs-12">
        @if (isset($products) && count($products))
            @foreach($products as $product)
                @if(count($product->category))
                {{-- */   $catalogs = app('Catalog')->getAllParents($product->category->first()->slug);   /* --}}
                <div class="product-item list-style-product grid-item row">
                    <div class="col-sm-3 col-xs-12 product-img-block">
                        <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}" class="link_modal">
                            <img src="{{ $product->getMainImage('md') }}" alt="{!! $product->$local !!}">
                        </a>
                    </div>
                    <div class="list-style-product-info col-sm-9 col-xs-12">
                        <div class="product-name">
                            <a href="{{ route('product.url',[$product->upi_id, $product->slug])  }}" class="link_modal">
                                {{ $product->$local }}
                            </a>
                        </div>
                        <div class="product-price">{{ $product->price }}{{$curency_code}}</div>
                        <div class="star_review_catalog" data-score="{{ count($product->review) ? round(collect($product->review)->sum('rating')/count($product->review)) : '' }}"></div>
                        <div class="product-likes-views clearfix">
                            <span>{{ trans('product.views') }}</span><span>{{ $product->views }}</span>
                            <span>{{ trans('product.sold') }}</span><span>{{ $product->sold }}</span>
                        </div>
                        <div class="catalog_names">
                                <span>
                                    <a href="{{ url('catalog/' . $catalogs->first()->slug) }}">
                                        {{ $catalogs->first()->$local }}
                                    </a>
                                </span>
                        </div>
                        <div class="catalog_names hidden">
                            {{-- */ $i = 1 /* --}}
                            @foreach($catalogs as $catalog)
                                <span>
                                    <a href="{{ url('catalog/' . $catalog->slug) }}">
                                        {{ $catalog->$local }}
                                    </a>
                                    {{ $i == count($catalogs) ? '' : '/'}}
                                </span>
                                {{-- */ $i ++ /* --}}
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        @else
            <img src="{{ asset('images/no-photo.jpg') }}" class="hidden">
            <div class="product-item list-style-product grid-item row" style="padding: 20px;">{{ trans('product.emptyCategory') }}</div>
        @endif
    </div>
</div>
<script>
    // stars for review list in search products
    $('.star_review_catalog').raty({
        number: function() {
            return $(this).attr('data-score');
        },
        path: '/images',
        readOnly: true,
        score: function() {
            return $(this).attr('data-score');
        }
    });
</script>