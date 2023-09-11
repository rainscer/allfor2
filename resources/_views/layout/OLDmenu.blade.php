<div class="main_menu clearfix hidden-sm hidden-xs">
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
    @foreach ($menu_items->level2 as $key => $catalog_item)
        {{-- */ $i = 0 /* --}}
        <div class="level-2" data-owner-id="{{ $key }}">
            @foreach ($catalog_item as $catalog_item_l2)
                @if($i % 2 == 0)
                    <div class="floating_left">
                        @endif
                        <div class="menu-link with-childrens" data-owner-id="{{ $catalog_item_l2->id }}">
                            <a href="{{ url('catalog/' . $catalog_item_l2->slug) }}">
                                {{  $catalog_item_l2->name_ru }}
                            </a>
                        </div>
                        <div class="level-3" data-owner-id="{{ $catalog_item_l2->parent_id }}">
                            @foreach ($catalog_item_l2->level3 as $catalog_item_level_3)
                                <div class="menu-link">
                                    @if($catalog_item_level_3->products->count() > 0)
                                        <a href="{{ url('catalog/' . $catalog_item_level_3->slug) }}">
                                            {{  $catalog_item_level_3->name_ru }}
                                        </a>
                                    @else
                                        <span class="colored-grey">
                                            {{  $catalog_item_level_3->name_ru }}
                                        </span>
                                        <img src="{{ asset('/images/soon.png') }}" class="soon_icon">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if((($i - 1) % 2 == 0) || ($i + 1 == count($catalog_item)))
                    </div>
                @endif
                {{-- */  $i++;  /* --}}
            @endforeach
        </div>
    @endforeach
</div>
<style>

</style>