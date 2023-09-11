@extends('admin.layout.default')
@section('content')

    @include('admin.catalogProducts.imageUploadScripts')

    <div class="panel panel-success">
        <div class="panel-heading">Edit product</div>
        <div class="panel-body">
            <script>
                $(function() {
                    $( "#upi_images" ).sortable({
                        items: "li:not(.ui-state-disabled2)"
                    });
                    $( "#upi_images" ).disableSelection();
                });

            </script>
            {!! Form::open(['url' => url('administrator/products/store')]) !!}

            <div class="col-xs-12 form-horizontal">

                @include('admin.catalogProducts._form')

                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group align-center">
                            {!! Form::submit('Save', ['class'=>'btn btn-success',
                                'name' => 'save']) !!}
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close()!!}
        </div>
    </div>
    <script>
        /*var editor = CKEDITOR.replace( 'description',{
         filebrowserBrowseUrl : '/elfinder/ckeditor'
         } );*/
    </script>
@endsection