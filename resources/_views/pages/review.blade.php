@extends('layout.default')
@section('content')
    <div class="product-reviews-head">Отзывы</div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
    {!! Form::open(array('action' => 'ProductController@searchReviews', 'id' => 'search_form_reviews', 'class' => 'form-horizontal'))!!}
    <div class="form-group">
    <div class="col-sm-11">
        {!! Form::text('search_review', isset($search_review) ? $search_review : '', ['class'=>'form-control', 'placeholder' => 'Название товара'] ) !!}
     </div>
    <div class="col-sm-1">
        {!! Form::submit('Поиск', ['class'=>'btn btn-primary']) !!}
    </div>
    </div>
    {!! Form::close()!!}
        </div>
    </div>
    <div class="grid prod-pad">
        @foreach($reviews as $review)
            <div class="product-item grid-item">
                <div class="review">
                    @if (isset($review->user->image))
                        <div class="user-image"><img src="{{ $review->user->image }}" alt="{!! $review->user->getFullName() !!}"></div>
                    @else
                        <div class="user-image"><img src="{{ asset('/images/user_profile.png') }}" alt="user profile"></div>
                    @endif
                    @if (is_null($review->user))
                        <div class="user-name">{{ $review->quest }}</div>
                        <div class="user-email">{{ trans('cart.guest') }}</div>
                    @else
                        <div class="user-name">{{ $review->user->getFullName() }}</div>
                        @if(is_null($review->user->social_url))
                        <div class="user-email"><a href="mailto:{{ $review->user->email }}" target="_blank">{{ $review->user->email }}</a></div>
                            @else
                                <div class="user-email"><a href="{{ $review->user->social_url }}" target="_blank">{{ $review->user->social_url }}</a></div>
                            @endif
                    @endif
                    <div class="product-title">
                        <a href="{{ route('product.url',[$review->product->upi_id, $review->product->slug]) }}" class="link_modal">
                            {{ $review->product->$local }}
                        </a>
                    </div>
                    <div class="review-text">{{ $review->text }}</div>
                </div>
                <span class="shadow"></span>
            </div>
        @endforeach
    </div>
@stop