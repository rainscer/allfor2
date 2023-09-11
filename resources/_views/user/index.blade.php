@extends('layout.default')

@section('content')
    <div class="profile">
        @include('user.header')

        @yield('user_content')
    </div>
@endsection

{{--@if(count($user->like))
    @include('user.likes_list')
@endif
<div class="white">
    @if(isset($friends))
        <p>Вконтакте:</p>
        <p>Ваши друзья:</p>
        <div class="form-group">
            <select id="friends" class="form-control" name="friends">
                <option value="{{ $user->social_id }}">Выберите друга или Ваш профиль по умолчанию</option>
                @foreach($friends as $friend)
                    <option value="{{ $friend->id }}" class="imagebacked" style="background-image: url('{{ $friend->photo }}');">
                        {{ $friend->first_name }}&nbsp;{{ $friend->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <textarea rows="8" class="message-vk form-control"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-default postVk">Сделать пост</button>
        </div>
    @endif
        @if(Session::has('user.facebook'))
            <div class="fb_post">
                <p>Facebook:</p>
                {{--<p>Ваши друзья:</p>
                <div class="form-group">
                    <select id="friends" class="form-control" name="friends">
                        <option value="{{ Auth::user()->social_id }}">Выберите друга или Ваш профиль по умолчанию</option>
                        @foreach($friends as $friend)
                            <option value="{{ $friend->id }}">
                                {{ $friend->first_name }}&nbsp;{{ $friend->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                --}}
{{--<div class="form-group">
    <textarea rows="8" class="message-fb form-control"></textarea>
</div>
<div class="form-group">
    <button class="btn btn-default postfb">Сделать пост</button>
</div>
</div>
@endif
<div class="alert alert-success hide" role="alert"></div>

<div class="text_header">Ваши отзывы:</div>
<div class="grid prod-pad">
<a class="anchor" name="reviews"></a>
@foreach($user->review as $review)
<div class="product-item grid-item">
<div class="review">
    @if (isset($review->product->image->first()->image_url))
        <div class="user-image"><img src="{{ image_asset($review->product->image->first()->image_url,'sm') }}" alt="{!! $review->product->$local !!}"></div>
    @else
        <div class="user-image"><img src="{{ asset('/images/user_profile.png') }}" alt="user profile"></div>
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
</div>--}}