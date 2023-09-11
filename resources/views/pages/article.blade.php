@extends('layout.app')
@section('content')
    <div class="article">
        <div class="article-text">{!! $article->$local_article_text !!}</div>
        @if(Auth::check())
            @if(Auth::user()->active)
            <a href="{{ route('administrator.articles.edit', $article->id) }}">{{ trans('admin.editArticle') }}</a>
            @endif
        @endif
    </div>
@endsection