@extends('admin.layout.default')

@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Меню
            <button type="button" class="btn btn-success navbar-right delete-admin-btn" data-owner-id="{{ url('/administrator/menu/delete') }}">Удалить</button>
            <button type="button" class="btn btn-success navbar-right edit-menu-btn" data-url="{{ url('/administrator/menu') }}">Изменить</button>
            <a href="{{ route('administrator.menu.create') }}" class="btn btn-success navbar-right new-menu-btn">Создать</a>
        </div>
        <div class="panel-body">
            <ul class="sortable" id="dop-menu-block" data-owner-id="{{ url('/administrator/menu/update-sort-menu') }}">
                @foreach($menu as $menu_item)
                    <li id="item-{{ $menu_item->id }}" data-owner-id="{{ $menu_item->id }}" class="menu-item">{{ $menu_item->name }}
                        @if($menu_item->type == 'href') <span class="grey">(Ссылка)</span>
                        @else <span class="grey">(Статья: {{ $menu_item->article->title_ru }})</span>
                        @endif
                        <input type="checkbox" class="delete-box-admin" id="menu-box-{{ $menu_item->id }}"
                               name="menu_item[]" value="{{ $menu_item->id }}">
                        <label for="menu-box-{{ $menu_item->id }}"><span></span></label></td>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection


