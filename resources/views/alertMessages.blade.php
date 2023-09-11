@if (count($errors))
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session()->has('message'))
    <div class="alert alert-success" role="alert">{!! session()->get('message') !!}</div>
@endif
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">{{ session()->get('success') }}</div>
@endif
@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! session()->get('error') !!}
    </div>
@endif