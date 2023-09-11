@extends('admin.layout.default')
@section('content')
    @include('admin.import')
    <div class="panel panel-success">
        <div class="panel-heading">Настройки
            <button type="button" class="btn btn-success navbar-right delete-admin-btn" data-owner-id="/administrator/settings/delete">Удалить</button>
            <a href="{{ url('administrator/settings/add') }}" class="btn btn-success navbar-right new-article-btn">Создать</a>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="setting_table table table-striped">
                    <tr>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Значение</th>
                        <th></th>
                    </tr>
                    @foreach($settings as $setting)
                        <tr id="item-{{ $setting->id }}">
                            <td><a href="{{ url('administrator/settings/'.$setting->id) }}">{!! $setting->key_name !!}</a></td>
                            <td>{{ $setting->description }}</td>
                            <td>{{ $setting->value }}</td>
                            <td><input type="checkbox" class="delete-box-admin" id="setting-box-{{ $setting->id }}" name="setting_item[]" value="{{ $setting->id }}">
                                <label for="setting-box-{{ $setting->id }}"><span></span></label></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection


