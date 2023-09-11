<div class="main_menu_mobile hidden-lg hidden-md">
    <ul class="menu_level1">
        @foreach ($menu_items->level1 as $catalog_item)
            <li data-owner-id="{{ $catalog_item->id }}">
                @if($catalog_item->icon)
                    <img class="menu-icon" src="{{ $catalog_item->icon }}">
                @endif
                <a class="uppercase-style" href="{{ url('catalog/' . $catalog_item->slug) }}">
                    {{  $catalog_item->name_ru }}
                </a>
                @if(isset($menu_items->level2[$catalog_item->id]) && count($menu_items->level2[$catalog_item->id]))
                    <span class="with-child roll-li-menu closed"></span>
                    <ul class="menu_level2">
                        @foreach ($menu_items->level2[$catalog_item->id] as $catalog_item_l2)
                            <li><a class="bold" href="{{ url('catalog/' . $catalog_item_l2->slug) }}">
                                    {{  $catalog_item_l2->name_ru }}
                                </a>
                                @if(count($catalog_item_l2->level3))
                                    <span class="with-child roll-li-menu closed"></span>
                                    <ul class="menu_level3">
                                        @foreach ($catalog_item_l2->level3 as $catalog_item_l3)
                                            <li>
                                                @if($catalog_item_l3->products->count() > 0)
                                                    <a href="{{ url('catalog/' . $catalog_item_l3->slug) }}">
                                                        {{  $catalog_item_l3->name_ru }}
                                                    </a>
                                                @else
                                                    <span class="colored-grey">
                                                        {{  $catalog_item_l3->name_ru }}
                                                    </span>
                                                    <img src="{{ asset('/images/soon.png') }}" class="soon_icon">
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>