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
            {!! Form::model($product,['url' => url('administrator/products/update/' . $product->id)]) !!}

            <div class="col-xs-12 form-horizontal">

                @include('admin.catalogProducts._form')

                <hr>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group align-center">
                            {!! Form::submit('Save', ['class'=>'btn btn-success',
                                'name' => 'save']) !!}
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group align-center">
                            @if($product->active)
                                @if($product->active->active)
                                    {!! Form::submit('Deactivate', ['class'=>'btn btn-primary',
                                     'name' => 'deactivate']) !!}
                                @else
                                    {!! Form::submit('Activate', ['class'=>'btn btn-primary',
                                    'name' => 'activate']) !!}
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group align-center">
                            {!! Form::submit('Cancel', ['class'=>'btn btn-danger',
                         'name' => 'cancel']) !!}
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