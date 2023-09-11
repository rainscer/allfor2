@extends('admin.layout.default')

@section('content')

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading font-bold">{{ $title }}</div>
            <div class="panel-body">
                {!! Form::model($entry, ['url' => url('administrator/advertisingCampaign/update/' . $entry->id), 'class' => 'form-horizontal']) !!}

                    @include('admin.advertisingCampaign._form')

                {!! Form::close()!!}

            </div>
        </div>
    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('jquery-colorbox/example3/colorbox.css') }}" type="text/css" />
@stop

@section('jsScript')
    <script type="text/javascript" src="{{ asset('/jquery-colorbox/jquery.colorbox-min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/standalonepopup_custom.js') }}"></script>
    <script>
        $("#product_id").prepend('<option value="0">Select name product</option>');
    </script>
@stop