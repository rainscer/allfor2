@extends('admin.layout.default')

@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-10">
                    Coupons
                </div>
                <div class="col-md-2 align-right">
                    <a href="{{ route('coupon.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Create Coupon</a>
                </div>
            </div>
        </div>
        <div class="panel-body">

            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Купон</th>
                    <th class="text-center">Количество / Использовано</th>
                    <th class="text-center">Скидка</th>
                    <th class="text-center">Годен до</th>
                    <th class="text-center">Добавлен</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
                </thead>
                <tbody class="list">
                    @foreach($coupons as $coupon)
                    <tr class="text-center">
                        <td>{{ $coupon->id }}</td>
                        <td><a href="{{ route('coupon.show', $coupon->id) }}">{{ $coupon->code }}</a></td>
                        <td>{{ $coupon->count }} / {{ $coupon->orders->count() }}</td>
                        <td>${{ $coupon->amount }}</td>
                        <td>{{ Carbon\Carbon::parse($coupon->expired_at)->format('d/m/Y') }}</td>
                        <td>{{ $coupon->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('coupon.edit', $coupon->id) }}"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {!! $coupons->render() !!}

        </div>
    </div>
@endsection