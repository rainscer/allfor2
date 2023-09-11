<div class="menu">
    @foreach (\App\Models\CatalogCategory::whereIsRoot()->get() as $catalog_item)

        <a href="{{ url('catalog/' . $catalog_item->slug) }}">
            {{  $catalog_item->name_ru . ', ' }}
        </a>
    @endforeach
</div>