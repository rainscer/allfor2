@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">
            {{ $title }}
        </div>
        <div class="panel-body">
            <div class="search-form2">
                {!! Form::open(['url' => url('administrator/advertisingCampaign/filter'), 'id' => 'form-entry', 'method' => 'get']) !!}
                <div class="input-group">
                    {!! Form::text(\App\Models\AdvertisingCampaign::getNameColumn(),
                                         Request::get(\App\Models\AdvertisingCampaign::getNameColumn(), null),
                                         ['class' => 'input-sm form-control','placeholder' => trans('admin.search')]) !!}

                    <span class="input-group-btn">
            <button class="btn btn-sm btn-default" type="submit">{{ trans('admin.go') }}!</button>
          </span>
                </div>
                {!! Form::close() !!}
            </div>

            {{--
            {!! Form::open(['url' => url('administrator/advertisingCampaign/action')]) !!}
            <div class="row wrapper p-b-45-only-xs">
                <div class="col-sm-8 m-b-xs">
                    <select class="input-sm form-control w-sm inline v-middle" name="action">
                        <option value="0">{{ trans('admin.entry_action') }}</option>
                        <option value="delete">{{ trans('admin.delete') }} {{ trans('admin.selected') }}</option>
                    </select>
                    {!! Form::submit(trans('admin.apply'), ['class' => 'btn btn-sm btn-danger']) !!}
                    <a href="{{ url('administrator/advertisingCampaign/create') }}" class="btn btn-sm btn-primary btn-addon">
                        <i class="fa fa-plus"></i>{{ trans('admin.create') }}</a>
                </div>
                <div class="col-sm-4">

                </div>
            </div>
            --}}
            <br>
            {!! Form::open(['url' => url('administrator/advertisingCampaign/action'), 'class'=>'sorted-table-form']) !!}
            <div class="product-action-block" style="display: inline-block;">
                <div class="btn-group action_on_selected  pull-left">
                    <button type="button" class="btn btn-default dropdown-toggle btn-action-upi-drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-owner-id="12">
                        {{ trans('user.actionOnSelected') }}
                        <span id="display_checked_count"></span>
                        <span class="glyphicon glyphicon-menu-down" style="margin-left: 10px;"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            {!! Form::submit(trans('user.delete'), ['name' => 'delete', 'class' => 'btn btn-link']) !!}
                        </li>
                        <li>
                            {!! Form::submit(trans('user.activate'), ['name' => 'activate', 'class' => 'btn btn-link']) !!}
                        </li>
                        <li>
                            {!! Form::submit(trans('user.deactivate'), ['name' => 'deactivate', 'class' => 'btn btn-link']) !!}
                        </li>
                    </ul>
                </div>
            </div>
            <a href="{{ url('administrator/advertisingCampaign/create') }}" class="btn btn-sm btn-primary btn-addon" style="float: left; padding: 6px 10px 6px; margin-right: 10px;">
                <i class="fa fa-plus"></i>{{ trans('admin.create') }}</a>


            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                    <tr>
                        <th style="width:20px;">
                            <label class="i-checks m-b-none">
                                {!! Form::checkbox('all', '1', null, ['class' => 'check-all']) !!}<i></i>
                            </label>
                        </th>
                        <th>{{ trans('admin.name') }}</th>
                        <th>{{ trans('admin.description') }}</th>
                        <th>{{ trans('admin.cost') }}</th>
                        <th>{{ trans('admin.link') }}</th>
                        <th>{{ trans('admin.product') }}</th>
                        <th>{{ trans('admin.visits') }}</th>
                        <th>{{ trans('admin.refunded_visits') }}</th>
                        <th>{{ trans('admin.phones') }}</th>
                        <th>{{ trans('admin.deals') }}</th>
                        <th>{{ trans('admin.start_date') }}</th>
                        <th>{{ trans('admin.end_date') }}</th>
                        <th>{{ trans('admin.active') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td>
                        </td>
                        <td>
                            Direct visits
                        </td>
                        <td>
                            Direct visits by link
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            {{  \App\Models\ItemAdvertisingCampaign::getCountByCampaign(\App\Models\ItemAdvertisingCampaign::NUMBER_VISITS) }}
                        </td>
                        <td>
                            {{  \App\Models\ItemAdvertisingCampaign::getCountByCampaign(\App\Models\ItemAdvertisingCampaign::NUMBER_REFUNDED) }}
                        </td>
                        <td>
                            {{  \App\Models\ItemAdvertisingCampaign::getCountByCampaign(\App\Models\ItemAdvertisingCampaign::NUMBER_PHONES) }}
                        </td>
                        <td>
                            {{  \App\Models\ItemAdvertisingCampaign::getCountByCampaign(\App\Models\ItemAdvertisingCampaign::NUMBER_DEALS) }}
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <i class="fa fa-check text-success text"></i>
                        </td>
                        <td>
                            <a href="{{ url('administrator/advertisingCampaign/view/0') }}"
                               class="btn m-b-xs btn-sm btn-default">
                                Details
                            </a>
                        </td>
                    </tr>

                    @foreach($records as $record)
                        @if($record->product)
                            <tr id="item-{{ $record->id }}" data-id="{{ $record->id }}">
                                <td>
                                    <label class="i-checks">
                                        {!! Form::checkbox('entries[]', $record->id, null, ['class' => 'entry-chk-box']) !!}
                                        <i></i></label>
                                </td>
                                <td>
                                    <a href="{{ url('administrator/advertisingCampaign/edit/' . $record->id ) }}" class="link" >
                                        {{ $record->name }}
                                    </a>
                                </td>
                                <td>
                                    {{  $record->description }}
                                </td>
                                <td>
                                    {{ $record->cost }}
                                </td>
                                <td>

                                    @if ($record->slug && $record->token)

                                        <button type="button" class="btn-copy-to-clip btn m-b-xs btn-sm btn-success btn-addon"
                                                data-clipboard-text="{{ url('/p/' . $record->slug .'/'. $record->token  ) }}"
                                                data-original-title="">
                                            <i class="fa fa-clipboard pull-right"></i>Copy
                                        </button>
                                        {{ url('/p/'. $record->slug .'/'. $record->token  ) }}
                                    @endif

                                </td>
                                <td>
                                    {{  $record->product->name_en }}
                                </td>
                                <td>
                                    {{  $record->itemAdvertisingCampaign->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_VISITS) }}
                                </td>
                                <td>
                                    {{  $record->itemAdvertisingCampaign->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_REFUNDED) }}
                                </td>
                                <td>
                                    {{  $record->itemAdvertisingCampaign->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_PHONES) }}
                                </td>
                                <td>
                                    {{  $record->itemAdvertisingCampaign->sum(\App\Models\ItemAdvertisingCampaign::NUMBER_DEALS) }}
                                </td>
                                <td>
                                    {{ $record->start_date ? $record->start_date->format('d/m/Y') : '' }}
                                </td>
                                <td>
                                    {{ $record->end_date ? $record->end_date->format('d/m/Y') : '' }}
                                </td>
                                <td>
                                    @if($record->active)
                                        <i class="fa fa-check text-success text"></i>
                                    @else
                                        <i class="fa fa-times text-danger text"></i>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('administrator/advertisingCampaign/view/' . $record->id) }}"
                                       class="btn m-b-xs btn-sm btn-default">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if ($records instanceof \Illuminate\Pagination\AbstractPaginator)
                {!! $records->render() !!}
            @endif
        </div>
    </div>
@endsection
@section('jsScript')
    <script type="text/javascript" src="{{ asset('clipboard/dist/clipboard.min.js') }}"></script>
    <script>

        clipboard = new Clipboard('.btn-copy-to-clip');

        // Tooltip

        $('.btn-copy-to-clip').tooltip({
            trigger: 'click',
            placement: 'bottom'
        });

        function setTooltip(message, target) {
            $(target).attr('data-original-title', message)
                    .tooltip('show');
        }

        function hideTooltip(target) {
            setTimeout(function() {
                $(target).tooltip('hide');
            }, 1000);
        }

        // Clipboard

        clipboard.on('success', function(e) {
            setTooltip('Copied!', e.trigger);
            hideTooltip(e.trigger);
        });

        clipboard.on('error', function(e) {
            setTooltip('Failed!', e.trigger);
            hideTooltip(e.trigger);
        });
    </script>
@stop