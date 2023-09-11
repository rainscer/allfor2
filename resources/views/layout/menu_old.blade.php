<div class="main_menu clearfix">
<!--div class="main_menu clearfix hidden-sm hidden-xs  hidden-lg hidden-md"-->
    <div class="level-1">
        @foreach ($menu_items->level1 as $catalog_item)
            <div class="menu-link with-childrens" data-owner-id="{{ $catalog_item->id }}" data-image="{{ $catalog_item->image }}">
                @if($catalog_item->icon)
                    <img class="menu-icon" src="{{ $catalog_item->icon }}">
                @endif
                <a href="{{ url('catalog/' . $catalog_item->slug) }}">
                    {{  $catalog_item->name_ru }}
                </a>
            </div>
        @endforeach
    </div>
</div>