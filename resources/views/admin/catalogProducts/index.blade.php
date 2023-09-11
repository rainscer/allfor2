@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">{{ $title }}</div>
        <div class="panel-body">
            {!! Form::open(['method' => 'get','id'=>'FormFilter']) !!}
            <div class="form-group">
                <div class="row">
                    {!! Form::label('category0', trans('user.selectCategories') ,['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::select('category0', $categoriesLevel0,
                        app('request')->get('category0',null),
                        ['class'=>'form-control category-sel','id' => 'category0', 'data-url' => url('administrator/products/get-category-children'),
                        'data-target' => '#category1'] ) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::select('category1', isset($categoriesLevel1) ? $categoriesLevel1 : [],
                        app('request')->get('category1',null),
                         ['class'=>'form-control category-sel','id' => 'category1', 'data-url' => url('administrator/products/get-category-children'),
                         'data-target' => '#category2'] ) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::select('category2', isset($categoriesLevel2) ? $categoriesLevel2 : [],
                        app('request')->get('category2',null),
                        ['class'=>'form-control','id' => 'category2', 'data-url' => url('administrator/products/get-category-children')] ) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-offset-3 col-sm-3">
                        <input class="form-control" placeholder="Search product ..." type="text"
                               value="{{ app('request')->get('filter-name',null) }}" name="filter-name">
                    </div>
                    <div class="col-sm-3">
                        {!! Form::checkbox('show_inactive_filter', 1, !$activeOnly) !!} Show not active products
                    </div>
                    <div class="col-sm-offset-3 col-sm-9 filter-btn">
                        <br>
                        {!! Form::text('sort', '', array('class' => 'hidden','id'=>'sort')) !!}
                        {!! Form::text('direction', '', array('class' => 'hidden','id'=>'direction')) !!}
                        {!! Form::submit('Фильтровать', ['class'=>'btn btn-primary']) !!}
                        <a href="{{ Request::url() }}" class="btn btn-danger">Сброс</a>
                        <a href="{{ url('administrator/products/create') }}" class="btn btn-success">Create new</a>
                        <a class="btn btn-info" href="{{ url('administrator/setRandViewSoldForProducts') }}">Добавить Просмотры/Заказы</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            <div id="sorted-table-container">

                {!! Form::open(['url' => url('administrator/products/action'), 'class'=>'sorted-table-form']) !!}
                <div class="product-action-block" style="display: inline-block;">
                    <div class="btn-group action_on_selected  pull-left">
                        <button type="button" class="btn btn-default dropdown-toggle btn-action-upi-drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-owner-id="12">
                            {{ trans('user.actionOnSelected') }}
                            <span id="display_checked_count"></span>
                            <span class="glyphicon glyphicon-menu-down" style="margin-left: 10px;"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
{{--                            <li>--}}
{{--                                {!! Form::submit(trans('user.delete'), ['name' => 'delete', 'class' => 'btn btn-link']) !!}--}}
{{--                            </li>--}}
                            <li>
                                {!! Form::submit(trans('user.activate'), ['name' => 'activate', 'class' => 'btn btn-link']) !!}
                            </li>
                            <li>
                                {!! Form::submit(trans('user.deactivate'), ['name' => 'deactivate', 'class' => 'btn btn-link']) !!}
                            </li>
                        </ul>
                    </div>
                </div>


                <table class="table product_table light-theme">
                    <thead>
                    <tr>
                        <th>
                            <label>
                                {!! Form::checkbox('all') !!}
                                <span></span>
                            </label>
                        </th>
                        <th>id</th>
                        <th>Image</th>
                        <th class="th_upi"><span class="sort {{ app('request')->get('sort','') == 'upi_id' ? app('request')->get('direction','') : '' }}" data-attribute="upi_id">UPI</span></th>
                        <th class="th_name"><span class="sort {{ app('request')->get('sort','') == 'name_en' ? app('request')->get('direction','') : '' }}" data-attribute="name_ru">Product Name</span></th>
                        <th>Category</th>
                        <th>Views</th>
                        <th>Active /<br>No active</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody class="list">
                    @foreach($products as $product)
                        <tr>
                            <td>
                                {!! Form::checkbox('entries[]', $product->id, null, ['class' => 'upi-chk-box']) !!}
                            </td>

                            <td >
                                {{ $product->id }}
                            </td>
                            <td class="product-user-img">
                                <img class="lazy2" width="50" src="{{ $product->getMainImage() }}">
                            </td>
                            <td >
                                {{ $product->getUpi() }}
                            </td>
                            <td>
                                <span class="name" >{{ $product->getName() }}</span>
                            </td>
                            <td class="category2">
                                @foreach($product->category as $category)
                                    {{ $category->getName() }}
                                    <br>
                                @endforeach
                            </td>
                            <td>
                                {{ $product->real_views }}
                            </td>
                            <td>
                                @if($product->active)
                                    @if($product->active->active)
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                        <span class="active hidden">active</span>
                                    @else
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        <span class="no-active hidden">no active</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="btn-group  pull-right">
                                    <a href="{{ url('administrator/products/edit/' . $product->id) }}" class="btn btn-default">Edit</a>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                {!! Form::close() !!}

                @if ($products instanceof \Illuminate\Pagination\AbstractPaginator)
                    {!! $products->render() !!}
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('input[name="all"]').change(function () {
                console.log('changes');
                $('input[name="entries[]"]').prop('checked', $(this).is(':checked'));
            });
        });
    </script>
@endsection