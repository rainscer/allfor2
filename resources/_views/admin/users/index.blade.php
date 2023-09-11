@extends('admin.layout.default')
@section('content')
    {{-- FIXME success_create alert --}}
    @if (session()->has('success_create'))
        <div class="alert alert-success" role="alert">Пользователь успешно создан!</div>
    @elseif (session()->has('success_update'))
        <div class="alert alert-success" role="alert">Пользователь успешно сохранен!</div>
    @endif
    <div class="panel panel-success">
        <div class="panel-heading">Пользователи
            <button type="button" class="btn btn-success navbar-right delete-admin-btn" data-owner-id="/administrator/users/delete">Удалить</button>
            <a href="{{ url('administrator/users/add') }}" class="btn btn-success navbar-right new-user-btn">Создать</a>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped" id="user_table">
                    <tr>
                        <th>Имя</th>
                        <th>E-mail</th>
                        <th>Дата регистрации</th>
                        <th>Last visit</th>
                        <th>Администратор?</th>
                        <th>Актив?</th>
                        <th>Удалить</th>
                    </tr>
                    @foreach($users as $user)
                        <tr id="item-{{ $user->id }}">
                            <td><a href="{{ url('administrator/users/'.$user->id) }}">{{ $user->getFullName() }}</a>
                                @if(array_key_exists($user->id, $users_online))
                                    <span class="badge">Online</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->updated_at }}</td>
                            <td>
                                {!! Form::checkbox('active', $user->id, $user->active or 0,
                                [
                                'class' => 'admin_user change-active-admin',
                                'data-owner-id' => '/administrator/users/update',
                                'id' =>'user-admin-'.$user->id
                                 ]
                                 ) !!}
                                <label for="user-admin-{{ $user->id }}"><span></span></label>
                            </td>
                            <td>
                                {!! Form::checkbox('isActive', $user->id, $user->isActive or 0,
                                 [
                                'class' => 'admin_user change-active-admin',
                                'data-owner-id' => '/administrator/users/update-active',
                                 'id' =>'user-admin-active-'.$user->id
                                 ]
                                 ) !!}
                                <label for="user-admin-active-{{ $user->id }}"><span></span></label>
                            </td>
                            <td>
                                {!! Form::checkbox('user_item[]', $user->id, null,
                                [
                                'class' => 'user-box delete-box-admin',
                                'id' =>'user-box-'.$user->id
                                ]
                                ) !!}
                                <label for="user-box-{{ $user->id }}"><span></span></label></td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if ($users instanceof \Illuminate\Pagination\AbstractPaginator)
                {!! $users->render() !!}
            @endif
            @if(isset($numberOfGuests))
                Кол-во гостей на сайте: <strong>{{ $numberOfGuests }}</strong>
            @endif
        </div>
    </div>
@endsection


