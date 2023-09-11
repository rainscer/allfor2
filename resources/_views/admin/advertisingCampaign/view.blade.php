@extends('admin.layout.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ $title }}
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped b-t b-light" id="campaign_views">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ trans('admin.date') }}</th>
                        <th>{{ trans('admin.product') }}</th>
                        <th>{{ trans('admin.visits') }}</th>
                        <th>{{ trans('admin.refunded_visits') }}</th>
                        <th>{{ trans('admin.phones') }}</th>
                        <th>{{ trans('admin.deals') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($records_grouped as $day => $record)
                        {{-- */ $record = collect($record) /*--}}
                        <tr style="font-weight: 900;" data-id="{{ $day }}">
                            <td>
                                <i class="open-list glyphicon glyphicon-arrow-down"></i>
                            </td>
                            <td colspan="2">
                                {{ $day }}
                            </td>
                            <td>
                                {{  $record->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_VISITS) }}
                            </td>
                            <td>
                                {{  $record->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_REFUNDED) }}
                            </td>
                            <td>
                                {{  $record->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_PHONES) }}
                            </td>
                            <td>
                                {{  $record->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_DEALS) }}
                            </td>
                        </tr>
                        @foreach($record->groupBy('product_id') as $item)
                            {{-- */ $item = collect($item) /*--}}
                            <tr data-action-id="{{ $day }}" class="detail_view">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    {{  $item->first()->product ? $item->first()->product->name_ru : 'No product' }}
                                </td>
                                <td>
                                    {{  $item->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_VISITS) }}
                                </td>
                                <td>
                                    {{  $item->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_REFUNDED) }}
                                </td>
                                <td>
                                    {{  $item->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_PHONES) }}
                                </td>
                                <td>
                                    {{  $item->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_DEALS) }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if ($records instanceof \Illuminate\Pagination\AbstractPaginator)
                {!! $records->render() !!}
            @endif
        </div>
    </div>
    <script>

    </script>
@endsection
