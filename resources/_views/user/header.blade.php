<div class="profile-user clearfix">
    <div class="profile_image">
        @if($user->image)
            <img src="{{ $user->image }}">
        @else
            <img src="{{ asset('images/user_profile.png') }}">
        @endif
    </div>
    <div class="profile-data container-fluid">
        <div class="profile-col">
            <p class="profile-username">{{ $user->getFullName() }}</p>
            <p class="profile-date"> {{ trans('user.memberSince') . $user->created_at . trans('user.memberYear')}} </p>
            <div class="profile-message mail-message hidden-xs">
                <span class="profile-message-icon"></span>
                <p class="hidden-sm">0</p>
            </div>
            @if(Auth::user()->name == 'support')
                {{-- */ $qaCount = Auth::user()->newQuestionsCount() /* --}}
            @else
                {{-- */ $qaCount = Auth::user()->newAnswersCount() /* --}}
            @endif
            @if((Auth::user()->newMessagesCount() + $qaCount > 0) || classActivePath('messages') != '')
                {{-- */  $classActive = true /* --}}
            @else
                {{-- */  $classActive = false /* --}}
            @endif
            <div class="profile-message hidden-xs {{ $classActive ? 'active' : '' }}
            {{ classActivePath('messages') }}{{ classActivePath('showQa') }}{{ classActivePath('getQa') }}">
                <a href="{{ url('messages') }}"><span class="profile-rewies-icon"></span></a>

                <p class="hidden-sm">{{ Auth::user()->newMessagesCount() + $qaCount }}</p>
            </div>
            <div class="row hidden-lg hidden-md hidden-sm">
                <div class="message-icons-block col-xs-3 profile-message">
                    <span class="profile-message-icon"></span>
                </div>
                <div class="message-icons-block col-xs-3 profile-message {{ $classActive ? 'active' : '' }}
                {{ classActivePath('messages') }}{{ classActivePath('getQa') }}{{ classActivePath('showQa') }}">
                    <a href="{{ url('messages') }}"><span class="profile-rewies-icon"></span></a>
                </div>
                <div class="message-icons-block col-xs-3 profile-message">
                    <a class="profile-setting {{ classActivePath('setting') }}" href="{{ url('user/setting') }}"><span></span></a>
                </div>
                <div class="message-icons-block col-xs-3 profile-message">
                    <a class="profile-logout" href="{{ url('auth/logout') }}"><img src="{{ asset('/images/logout.png') }}"></a>
                </div>
            </div>
        </div>
        <div class="profile-col profile-col-2 row">
            <div class="profile-counts col-xs-4 {{ classActivePath('visited') }}">
                <a href="{{ url('user/visited') }}">
                    <p class="number">{{ isset($user->product_viewed_count) ? $user->product_viewed_count : 0 }}</p>
                    <p>{{ trans('user.visited') }}</p>
                </a>
            </div>
            <div class="profile-counts col-xs-4 {{ classActivePath('likes') }}">
                <a href="{{ url('user/likes') }}">
                    <p class="number count-likes">{{ count($user->like) }}</p>
                    <p>{{ trans('user.like') }}</p>
                </a>
            </div>
            <div class="profile-counts col-xs-4 {{ classActivePath('orders/delivered') }}">
                <a href="{{ url('user/orders/delivered') }}">
                    <p class="number">{{ $user->delivered }}</p>
                    <p>{{ trans('user.delivered') }}</p>
                </a>
            </div>
            <div class="profile-counts col-xs-4 {{ classActivePath('orders/paid') }}">
                <a href="{{ url('user/orders/paid') }}">
                    <p class="number">{{ $user->paid }}</p>
                    <p>{{ trans('user.inWay') }}</p>
                </a>
            </div>
            <div class="profile-counts col-xs-4 {{ classActivePath('orders/waiting') }}">
                <a href="{{ url('user/orders/waiting') }}">
                    <p class="number">{{ $user->not_paid }}</p>
                    <p>{{ trans('user.notPaid') }}</p>
                </a>
            </div>
        </div>
        <a class="hidden-xs profile-setting {{ classActivePath('setting') }}" href="{{ url('user/setting') }}" data-toggle="popover" data-placement="bottom"><span></span></a>
        <a class="hidden-xs profile-logout" href="{{ url('auth/logout') }}"><img src="{{ asset('/images/logout.png') }}"></a>
        @if($user->active)
            <a class="administrator-permision" href="{{ url('administrator') }}" target="_blank"><img src="{{ asset('/images/admin.png') }}"></a>
            <a class="administrator-permision set-as-support" href="{{ url('user/set-as-support') }}"><img src="{{ asset('/images/operator_support_boy-512.png') }}"></a>
        @endif
    </div>
</div>

<div class="hidden popover-info">
    <div class="setting_title">{{ trans('user.fillAndEditData') }}</div>
</div>