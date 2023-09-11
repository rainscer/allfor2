@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Лог поисковых фраз
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped product_table">
                    <tr>
                        <th>Поисковая фраза</th>
                        <th>Точное вхождение</th>
                        <th>Дата</th>
                    </tr>
                    @foreach($search_words as $word)
                        <tr>
                            <td class="name">{{ $word->words }}</td>
                            <td>{!! $word->checked == 0 ? 'нет' : 'да' !!}</td>
                            <td>{!! $word->created_at->format('d-m-Y') !!}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if ($search_words instanceof \Illuminate\Pagination\AbstractPaginator)
                {!! $search_words->render() !!}
            @endif
        </div>
    </div>
@endsection
