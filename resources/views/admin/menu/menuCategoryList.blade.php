@extends('admin.layout.default')

@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Меню категорий
            <button type="button" class="btn btn-success navbar-right edit-menu-btn" data-url="{{ url('/administrator/editMenuCategory') }}">Изменить</button>
        </div>
        <div class="panel-body">
            <ul class="sortable" id="dop-menu-block" data-owner-id="{{ url('/administrator/menuCategory/update-sort-menu') }}">
                @foreach($menuCategory as $item)
                    <li data-owner-id="{{ $item->id }}" class="menu-item clearfix">
                    <span class="thumb_menu">
                        <img src="{{ $item->image ? asset($item->image) : asset('images/no-photo.jpg') }}">
                    </span>
                        <span>
                            {{ $item->name_ru }}
                            <img class="menu-admin-icon" style="width: 20px;" src="{{ $item->icon ? asset($item->icon) : asset('images/no-photo.jpg') }}">
                        </span>
                        <input type="checkbox" class="delete-box-admin" id="menu-box-{{ $item->id }}"
                               name="menu_item[]" value="{{ $item->id }}">
                        <label for="menu-box-{{ $item->id }}"><span></span></label>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection