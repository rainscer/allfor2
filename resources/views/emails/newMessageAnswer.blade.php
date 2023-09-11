@extends('emails.default')

@section('content')
    <div>
        <p style="color: #565656; font-size: 14px"><strong>{{ $qa->email }}!</strong> Добрый день</p>
    </div>
    <div>
        <p style="color: #565656; font-size: 14px"><strong>Ответ на Ваш вопрос:</strong> ({{ $qa->text }})</p>
        <p style="color: #565656; font-size: 14px">{{ $qa->answer }}</p>
    </div>
@endsection