{{-- */ $newReviews = app('Review')->getNewReviewsCount();
        $newOrders = app('Order')->getNewCount(); /*--}}
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}" target="_blank">AllFor2</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Пользователи <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/administrator/users') }}">Список пользователей</a></li>
                        <li><a href="{{ url('/administrator/users/online') }}">Пользователи онлайн</a></li>
                        <li><a href="{{ url('/administrator/users/add') }}">Добавить нового</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Статьи<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('administrator.articles.index') }}">Список статей</a></li>
                        <li><a href="{{ route('administrator.articles.create') }}">Добавить новую статью</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Меню<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('administrator.menu.index') }}">Список меню</a></li>
                        <li><a href="{{ route('administrator.menu.create') }}">Добавить новый пункт меню</a></li>
                        <li><a href="{{ url('/administrator/menuCategoryList') }}">Список меню каталога</a></li>
                        <li><a href="{{ url('/administrator/categoryMenu') }}">Загрузить фото для меню каталога</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Заказы
                        @if($newOrders > 0)
                            <span class="badge">{{ $newOrders }}</span>
                        @endif
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>{!! HTML::linkRoute('orders', 'Все заказы') !!}</li>
                        <li>{!! HTML::linkRoute('orders', 'Ожидается оплата', array('waiting')) !!}</li>
                        <li>{!! HTML::linkRoute('orders', 'Оплачено', array('paid')) !!}</li>
                        <li>{!! HTML::linkRoute('orders', 'Доставлено', array('delivered')) !!}</li>
                        <li><a href="{{ url('administrator/check-order-liqpay') }}">Проверка заказа по Liqpay</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Каталог<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('administrator/products') }}">Каталог</a></li>
                        <li><a href="{{ url('administrator/catalog-category') }}">Категории</a></li>
                        <li><a href="{{ route('coupon.index') }}">Купоны</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Импорт<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('administrator/settings') }}">Импорт и настройки</a></li>
                        <li><a href="{{ url('administrator/scheduler') }}">Scheduler</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Рассылка<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('administrator/mail') }}">Письма</a></li>
                        <li><a href="{{ url('administrator/mail/archived') }}">Архив писем</a></li>
                        <li><a href="{{ url('administrator/mail/create') }}">Создать письмо</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Отзывы
                        @if($newReviews > 0)
                            <span class="badge">{{ $newReviews }}</span>
                        @endif
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ url('/administrator/reviews') }}">Отзывы
                                @if($newReviews > 0)
                                    <span class="badge">{{ $newReviews }}</span>
                                @endif
                            </a></li>
                        <li>
                            <a href="{{ url('/administrator/reviews/archived') }}">
                                Архив отзывов
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- */ $newCalls = app('CallMeService')->getCountNotCompleted() /* --}}
                <li><a href="{{ url('/administrator/call-me') }}">Звонки
                        @if($newCalls > 0)
                           <span class="badge">{{ $newCalls }}</span>
                        @endif
                    </a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Logs<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/administrator/systemError') }}">Ошибки</a></li>
                        <li><a href="{{ url('administrator/job-logs') }}">Job logs</a></li>
                        <li><a href="{{ url('administrator/catalog/product-search-log') }}">Лог поиска</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ url('administrator/advertisingCampaign') }}">Кампании</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->getFullName() }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('auth/logout') }}">Logout</a></li>
                        </ul>
                    </li>
            </ul>
        </div>
    </div>
</nav>