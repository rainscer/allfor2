@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Ошибки
            <a href="systemError/delete" class="btn btn-success navbar-right delete-all-errors-btn">Удалить все ошибки</a></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    @foreach($systemErrors as $systemError)
                        <tr>
                            <td>{{ $systemError->created_at }}</td>
                            <td>{{ inet_ntop($systemError->ip_address) }}</td>
                            <td>{{ $systemError->user ? $systemError->user->getFullName() : 'System' }}</td>
                            <td class="modalFormToggle" href="{{ url('administrator/systemError/trace/'.$systemError->id) }}">
                                {{ $systemError->error }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {!! $systemErrors->render() !!}
            </div>
        </div>
    </div>

@endsection