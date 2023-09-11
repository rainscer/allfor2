<div class="main_menu clearfix hidden-lg hidden-md">
    <div class="row">
        <div class=" col-xs-offset-4 col-xs-4 user_header">
            @if (Auth::check())
                @if(isset($authUser->image))
                    <a href="{{ url('user') }}" class="image_user_link"><img src="{{ $authUser->image }}" class="user_icon" alt="user"></a>
                @else
                    <a href="/user" class="image_user_link">
                        <img src="{{ asset('/images/user-profile.png') }}" class="user_icon" alt="user">
                    </a>
                @endif
                {{--<a href="{{ url('auth/logout') }}" class="user_link_logout white_color">{{ trans('home.logout') }}</a>--}}
            @else
                <a href="#modal_user" class="user_foto_header" data-toggle="modal">
                    <img src="{{ asset('/images/user-profile.png') }}" class="user_icon" alt="user">
                </a>
                {{--<a href="#modal_user" class="user_link_logout white_color" data-toggle="modal">{{ trans('home.login') }}</a>--}}
            @endif
        </div>
        <div class=" col-xs-4 call_header">
            <a class="call-back" href="{{ Auth::check() ? '#callBack' : '#callBack' }}" data-toggle="modal">
                <img src="{{ asset('/images/svjaz.png') }}" class="call_icon" alt="call back">
            </a>
        </div>
    </div>
</div>