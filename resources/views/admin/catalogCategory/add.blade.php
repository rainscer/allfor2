    <h3 style="text-align: center;">
        Add new Node {{ $direct }} {{ $target_node->name  }}
    </h3>
<br>

{!! Form::open(array('url' => array('administrator/catalog-category/add/'.$direct.'/'.$target_node->id),'class' => 'form-horizontal')) !!}

<div class="form-group">
    {!! Form::label('name_en', 'name:', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
        {!! Form::text('name_en',null ,['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
{!! Form::label('description_en', 'description:', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
{!! Form::text('description_en',null ,['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('slug', 'slug:', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
        {!! Form::text('slug',null ,['class' => 'form-control']) !!}
    </div>
</div>

{!! Form::submit('Save changes',['class' => 'btn btn-primary pull-right','id' => 'ajaxEditNode']) !!}

{!! Form::close() !!}