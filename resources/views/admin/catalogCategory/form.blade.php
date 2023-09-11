<div class="center-block">
    <a href="catalog-category/addForm/after/{{ $catalog->id }}" class="btn btn-primary ajaxAddNode">Add New (after)</a>
    <a href="catalog-category/addForm/inside/{{ $catalog->id }}" class="btn btn-success ajaxAddNode">Add New (inside)</a>
    <a href="catalog-category/delete/{{ $catalog->id }}" class="btn btn-danger ajaxDeleteNode">Delete</a>

</div>
<h3 style="text-align: center;">
    Edit node -> {{ $catalog->name  }}
</h3>
<br>

{!! Form::model($catalog, ['url' => ['administrator/catalog-category/update/'.$catalog->id],'class' => 'form-horizontal']) !!}


<div class="form-group">
    {!! Form::label('name_en', 'name:', ['class' => 'col-sm-4 control-label required']) !!}
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
        {!! Form::text('name_en',null ,['class' => 'form-control','required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('description_en', 'description:', ['class' => 'col-sm-4 control-label required']) !!}
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
        {!! Form::text('description_en',null ,['class' => 'form-control','required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('slug', 'slug:', ['class' => 'col-sm-4 control-label required']) !!}
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
        {!! Form::text('slug',null ,['class' => 'form-control','required']) !!}
        <span style="color: #777; font-size: 11px;">* This field can not contain spaces. Instead of spaces you can use "-"</span>
    </div>
</div>

<div class="form-group">
    <span class="col-xs-offset-4 col-xs-8 help-block wrong_upi"></span>
</div>

{!! Form::submit('Save changes',['class' => 'btn btn-primary pull-right','id' => 'ajaxEditNode']) !!}

{!! Form::close() !!}
