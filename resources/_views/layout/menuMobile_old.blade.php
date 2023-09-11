<div class="main_menu_mobile hidden-xs hidden-sm hidden-lg hidden-md">
<!--div class="main_menu_mobile hidden-sm hidden-xs hidden-lg hidden-md"-->
    <ul class="menu_level1">
        @foreach ($menu_items->level1 as $catalog_item)
            <li data-owner-id="{{ $catalog_item->id }}">
                @if($catalog_item->icon)
                    <img class="menu-icon" src="{{ $catalog_item->icon }}">
                @endif
                <a class="uppercase-style" href="{{ url('catalog/' . $catalog_item->slug) }}">
                    {{  $catalog_item->name_ru }}
                </a>
            </li>
        @endforeach
    </ul>
</div>