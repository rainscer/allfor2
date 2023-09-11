@extends('admin.layout.default')
@section('content')

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading font-bold">{{ $title }}</div>
            <div class="panel-body">
                {!! Form::open(['url' => url('administrator/advertisingCampaign/store'), 'class' => 'form-horizontal']) !!}

                    @include('admin.advertisingCampaign._form')

                {!! Form::close()!!}

            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('jquery-colorbox/example3/colorbox.css') }}" type="text/css" />

    <script>
        $(document).ready(function() {
            $(document).on('change', '.add-token', function()
            {
                if ($('.token').val() == '') {
                    $('.token').val(generateRandomString(20));
                }
            });
        });
        $("#product_id").prepend('<option value="0">Select name product</option>');
        if ($("#product_id option:not(:selected)"))
        {
            $("#product_id option:first").attr('selected','selected');
        }

     </script>
@stop