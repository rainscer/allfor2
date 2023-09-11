@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Каталог товаров
            {!! Form::model($sort,array('route' => 'product.search'))!!}
            {!! Form::text('search_word', null, array('placeholder'=>'Поиск','class'=>'search-input-product-admin'))!!}
            {!! Form::select('sort', $sort->sorting_array,null,['class' => 'form-control sort-form'])  !!}
            {!! Form::submit('Фильтр', array('class'=>'btn btn-primary'))!!}
            {!! Form::close()!!}
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped product_table" id="product_table">
                    <tr>
                        <th>Название товара</th>
                        <th>Категория</th>
                        <th>UPI_ID</th>
                        <th>Просмотры</th>
                        <th>Активный?</th>
                        <th>Ожидается?</th>
                    </tr>
                    @foreach($products as $product)
                        <tr>
                            <td class="name">
                                <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}" class="link_modal">{!! $product->name_ru !!}</a></td>
                            <td>
                                @if (isset($product->category->first()->name_ru))
                                    {{ $product->category->first()->name_ru }}
                                @endif
                            </td>
                            <td>{!! $product->upi_id !!}</td>
                            <td>{!! $product->real_views !!}</td>
                            <td>
                                @if (isset($product->active))
                                    {!! Form::checkbox('active', $product->id, $product->active->active or 0,['class' => 'admin_catalog change-active-admin','data-owner-id' => '/administrator/catalog/update', 'id' =>'catalog-admin-'.$product->id])  !!}
                                    <label for="catalog-admin-{{ $product->id }}"><span></span></label>
                                @endif
                            </td>
                            <td>
                                {!! Form::checkbox('hidden', $product->id, $product->hidden or 0,['class' => 'admin_catalog change-hidden-admin','data-owner-id' => url('/administrator/catalog/update-hidden'), 'id' =>'catalog-admin-h-'.$product->id])  !!}
                                <label for="catalog-admin-h-{{ $product->id }}"><span></span></label>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if ($products instanceof \Illuminate\Pagination\AbstractPaginator)
                {!! $products->render() !!}
            @endif
        </div>
    </div>

@stop
