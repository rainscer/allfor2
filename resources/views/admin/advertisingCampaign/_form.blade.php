<div class="form-group">
    {!! Form::label('name',trans('admin.name'),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', null, ['class'=>'form-control','required'] ) !!}

        @if ($errors->has('name'))
            <p class="text-red">{{ $errors->first('name') }}</p>
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('description',trans('admin.description'),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('description', null, ['class'=>'form-control'] ) !!}

        @if ($errors->has('description'))
            <p class="text-red">{{ $errors->first('description') }}</p>
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('cost',trans('admin.cost'),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('cost', null, ['class'=>'form-control'] ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('slug',trans('admin.slug'),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
            {!! Form::text('slug', null, ['class' => 'form-control add-token']) !!}

        @if ($errors->has('slug'))
            <p class="text-red">{{ $errors->first('slug') }}</p>
        @endif
    </div>
</div>
<div class="form-group">
    {!! Form::label('token',trans('admin.token'),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('token', null, ['class'=>'form-control token'] ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('product_id',trans('admin.product'),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('product_id', \App\Models\CatalogProduct::select('id', \Illuminate\Support\Facades\DB::raw("CONCAT(upi_id, ' - ', name_en) as name"))->lists('name','id'), null, ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('startDate',trans('admin.start_date'),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        <div class="input-group w-md">
            {!! Form::text('startDate', isset($entry) ? ($entry->start_date ? $entry->start_date->format('d/m/Y') : null) : null, ['class'=>'form-control datepicker'] ) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('endDate',trans('admin.end_date'),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        <div class="input-group w-md">
            {!! Form::text('endDate', isset($entry) ? ($entry->end_date ? $entry->end_date->format('d/m/Y') : null) : null, ['class'=>'form-control datepicker'] ) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label class="i-checks">
                {!! Form::checkbox('active') !!}<i></i> {{ trans('admin.active') }}
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit(trans('admin.save'), ['class'=>'btn btn-primary']) !!}
    </div>
</div>
<script type="text/javascript">
    $('select').select2();
</script>
