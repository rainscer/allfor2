@extends('admin.layout.default')

@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Categories</div>
        <div class="panel-body">
            <div class = 'row'>
                <div class="col-md-4 ">
                    <div id="categoryTree" data-url="{{ url('administrator/catalog-category/getTree') }}"
                         data-get-node-url="{{ url('administrator/catalog-category/getNode') }}"
                         data-move-node-url="{{ url('administrator/catalog-category/moveNode') }}">

                    </div>
                </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-6 node-edit">
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
    </div>
@endsection