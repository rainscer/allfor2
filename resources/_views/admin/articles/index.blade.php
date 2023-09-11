@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Статьи
            <button type="button" class="btn btn-success navbar-right delete-admin-btn"
                    data-owner-id="{{ url('administrator/articles/delete') }}">Удалить</button>
            <a href="{{ route('administrator.articles.create') }}" class="btn btn-success navbar-right new-article-btn">Создать</a>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="article_table table table-striped" id="article_table">
                    <tr>
                        <th>Название ru</th>
                        <th>Ссылка на статью</th>
                        <th></th>
                    </tr>
                    @foreach($articles as $article)
                        <tr id="item-{{ $article->id }}">
                            <td><a href="{{ route('administrator.articles.edit', $article->id) }}">{!! $article->title_ru !!}</a></td>
                            <td><a href="{{ url('read/'.$article->slug) }}" target="_blank">{{ url('read/'.$article->slug) }}</a></td>
                            <td><input type="checkbox" class="delete-box-admin" id="article-box-{{ $article->id }}"
                                       name="article_item[]" value="{{ $article->id }}">
                                <label for="article-box-{{ $article->id }}"><span></span></label></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection


