<div class="col-xs-2 col-sm-2 category" onclick="$('.top_main_menu').slideToggle(250);">
    <!--div class="col-xs-2 col-sm-2 hidden-md hidden-sm hidden-lg category" onclick="$('.top_main_menu').slideToggle(250);"-->
    <div class="category-icon">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </div>
    {{--<a class="dropdown-menu-trigger menu-head white_color" id="departments">{{ trans('home.categories')}}
    </a>--}}
    {{-- */  $menu_items = app('Catalog')->getMenuItems() /* --}}
    <div class="top_main_menu">
        @include('layout.menuMobile_old')
        @include('layout.menu_old')
    </div>
</div>



