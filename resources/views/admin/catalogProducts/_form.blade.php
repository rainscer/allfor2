<div class="form-group">
    {!! Form::label('name_en','Name',['class'=>'col-sm-2 control-label required']) !!}
    <div class="col-sm-10">
        @if ($errors->has('name_en'))
            <span class="help-block">
                                   <strong>{{ $errors->first('name_en') }}</strong>
                               </span>
        @endif
        {!! Form::text('name_en', null, ['class'=>'form-control', 'id' => 'name'] ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('slug','Slug',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('slug', null, ['class'=>'form-control','id' => 'slug'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('weight','Weight',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('weight', null, ['class'=>'form-control','id' => 'weight'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('price','Price',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('price', null, ['class'=>'form-control','id' => 'price'] ) !!}
    </div>
</div>

<div class="row">
    {!! Form::label('delivery_type1','Delivery type',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-xs-10">
        <div style="margin-bottom:20px;">
            <label class="radio-inline">
                {!! Form::radio('delivery_type', 0, isset($product) ? ($product->delivery_type ? false : true) : false) !!} от 10 до 25-ти рабочих дней
            </label>
            <label class="radio-inline">
                {!! Form::radio('delivery_type', 1, isset($product) ? ($product->delivery_type ? true : false) : true) !!} 48 hours
            </label>
        </div>
    </div>
</div>


<div class="form-group">
    {!! Form::label('category0','Category',['class'=>'col-sm-2 control-label required']) !!}
    <div class="col-sm-3">
        {!! Form::select('category0', $categoriesLevel0,
        isset($product->catlvl0) ? $product->catlvl0 : null,
        ['class'=>'form-control category-sel','id' => 'category0', 'data-url' => url('administrator/products/get-category-children'),
        'data-target' => '#category1'] ) !!}
    </div>
    <div class="col-sm-3">
        {!! Form::select('category1', isset($categoriesLevel1) ? $categoriesLevel1 : [],
        isset($product->catlvl1) ? $product->catlvl1 : null,
         ['class'=>'form-control category-sel','id' => 'category1', 'data-url' => url('administrator/products/get-category-children'),
         'data-target' => '#category2'] ) !!}
    </div>
    <div class="col-sm-3">
        {!! Form::select('category2', isset($categoriesLevel2) ? $categoriesLevel2 : [],
        isset($product->catlvl2) ? $product->catlvl2 : null,
        ['class'=>'form-control','id' => 'category2', 'data-url' => url('administrator/products/get-category-children')] ) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-2 col-md-2 col-lg-2  align-right">
        {!! Form::label('images', 'Images', ['class' => 'control-label']) !!}
        <div class="label-desc">
            Max 10 photos<br>
            First photo is a main
            <br><br>
            Use Ctrl to add several photo
        </div>
    </div>
    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
        <ul id="upi_images" class="clearfix">
            {{-- */ $count_images = 0 /*--}}
            @if(isset($product))

                @foreach($product->image->sortBy('sort') as $image)
                    <li class="upi-image-preview-block ui-state-default solid-border uploaded-item" id="block-{{ $image->id }}">
                        <div>
                            <img src="{{ image_asset($image->image_url,'lg') }}" class="upi-image-preview">
                            <input type="hidden" value="{{ $image->id }}" name="images[]">
                            <button type="button" class="delete-image-btn" data-owner-id="{{ $image->id }}"
                                    data-url="{{ url('/deleteImage/upi') }}" data-selector="upi_images"></button>
                        </div>
                    </li>
                    {{-- */ $count_images++ /*--}}
                @endforeach

            @endif

            @while($count_images < 10)
                <li class="ui-state-default ui-state-disabled2">
                    <span class="add-image-span bordered-add-image">+</span>
                </li>
                {{-- */ $count_images++ /*--}}
            @endwhile
        </ul>
        <div class="form-group" style="margin: 10px 0 0 0;">
            <div class="progress-file-uploads">

                <div id="progress" class="progress">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
            </div>
        </div>
        <div id="errorLimit">
            You have exceeded the limit of downloads. You can upload no more then <strong>
                <span class="number_uploads">10</span></strong> images
        </div>
    </div>
</div>

<div id="fileupload-div">
    <input id="fileupload" type="file" name="files[]" multiple accept="image/jpeg,image/gif,image/png">
</div>


<div class="form-group">
    {!! Form::label('description_en','Description',['class'=>'col-sm-2 control-label required']) !!}
    <div class="col-sm-10">
        @if ($errors->has('description_en'))
            <span class="help-block">
                                   <strong>{{ $errors->first('description_en') }}</strong>
                               </span>
        @endif
        {!! Form::textarea('description_en', null, ['class' => 'form-control']) !!}
    </div>
</div>