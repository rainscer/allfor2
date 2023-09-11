@if(isset($menu) || isset($catalog_active))
    <div class="dopmenu grid-item">
        @if(isset($menu))
            <div class="dopmenu-article">
                @foreach($menu as $item)
                    <div class="dopmenu-item">
                        @if($item->type == 'href')
                            <a href="{{ url($item->content) }}">{{ $item->name }}</a>
                        @else
                            <a href="{{ url('read/'.$item->article->slug) }}">{{ $item->name }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if(isset($catalog_active))
            <input type="hidden" id="current_category" value="{{ $catalog_active->slug }}">
            {{-- */ $catalog_parents = app('Catalog')->getAllParents($catalog_active->slug);
                    $i = 0; /* --}}
            <ul class="dopmenu-ul parents-menu">
                @foreach($catalog_parents as $catalog_parent)
                    <li class="dopmenu-item">
                        @if($catalog_active->id == $catalog_parent->id)
                            <span class="margin-left-{{ $i }}">
                        {{ $catalog_parent->$local }}
                    </span>
                        @else
                            <a href="{{ url('catalog/'.$catalog_parent->slug) }}" class="colored-link margin-left-{{ $i }}">
                                {{ $catalog_parent->$local }}
                            </a>
                        @endif
                    </li>
                    {{-- */ $i++ /* --}}
                @endforeach
            </ul>
            <br>

            @if(isset($catalog_sub_menu) && count($catalog_sub_menu))
                <ul class="dopmenu-ul child-menu">
                    @foreach($catalog_sub_menu as $item)
                        <li class="dopmenu-item">
                    <span class="parent-span">
                        <a class="colored-link parent-cat" href="{{ url('catalog/'.$item->slug) }}">{{ $item->$local }}</a>
                    </span>
                            @if(isset($item->subcategory) && count($item->subcategory))
                                <span class="roll-li-category-menu"></span>
                                <ul>
                                    @foreach($item->subcategory as $subcategory)
                                        <li>
                                            <a class="colored-link child-cat" href="{{ url('catalog/'.$subcategory->slug) }}">
                                                {{ $subcategory->$local }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        @endif
    </div>
@endif