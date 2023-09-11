@extends('admin.layout.default')

@section('content')
    @include('admin.menu.menuCategoryScripts')

    <div class="container">

        {!! Form::model($category, array('route' => array('menuCategory.update', $category->id), 'class' => 'form-horizontal menu-category-form')) !!}
        <div class="form-group">
            <div class="col-sm-12">
                Категория: {{ $category->$local }}
            </div>
        </div>

        <!-- The container for the uploaded files -->
        <div class="form-group">
            <div class="col-sm-12">
        <p>Выберите изображение</p>
        <div id="files" class="files radio-inputs row">
            {{-- */ $i = 0; /* --}}
            @foreach($fileNames as $key => $file)
                {{-- */ $id = str_replace('.','separator', $key); /* --}}
                <div class="menu-image-preview-block col-md-3 col-sm-12" id="block-{{ $id }}">
                    <img src="{{ $file }}" class="menu-image-preview">
                    <input type="radio"
                           id="image_radio{{ $i }}"
                           name="image_radio"
                           class="menu-image-preview-radio"
                           value="{{ $file }}"
                            {{ $category->image == $file ? 'checked':'' }}>
                    <label for="image_radio{{ $i }}"><span></span></label>
                    <button type="button" class="delete-btn" data-owner-id="{{ $id }}" data-url="{{ url('administrator/deleteImage/background') }}"></button>
                </div>
                {{-- */ $i++ /* --}}
                @endforeach
        </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <p>или загрузите и затем выберите</p>
                <!-- The fileinput-button span is used to style the file input field as button -->
    <span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <!-- The global progress bar -->
                <br>
                <div id="progress" class="progress">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
            </div>
        </div>

        <div>Menu ICONS</div>
        <!-- The container for the uploaded files -->
        <div class="form-group">
            <div class="col-sm-12">
                <p>Выберите изображение</p>
                <div id="files-icon" class="files radio-inputs row">
                    {{-- */ $i = 0; /* --}}
                    @foreach($fileIconNames as $key => $file)
                        {{-- */ $id = str_replace('.','separator', $key); /* --}}
                        <div class="menu-image-preview-block menu-preview-icon col-md-1 col-md-offset-1 col-sm-6" id="block-icon-{{ $id }}">
                            <img src="{{ $file }}" class="menu-image-preview">
                            <input type="radio"
                                   id="image_icon_radio{{ $i }}"
                                   name="image_icon_radio"
                                   class="menu-image-preview-radio"
                                   value="{{ $file }}"
                                    {{ $category->icon == $file ? 'checked':'' }}>
                            <label for="image_icon_radio{{ $i }}"><span></span></label>
                            <button type="button" class="delete-icon-btn" data-owner-id="{{ $id }}"
                                    data-url="{{ url('administrator/deleteImage/icon') }}"></button>
                        </div>
                        {{-- */ $i++ /* --}}
                    @endforeach
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <p>или загрузите и затем выберите</p>
                <!-- The fileinput-button span is used to style the file input field as button -->
    <span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload-icon" type="file" name="files[]" multiple>
    </span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <!-- The global progress bar -->
                <br>
                <div id="progress-icon" class="progress">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-12">
                {!! Form::submit('Сохранить', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
        {!! Form::close()!!}
    </div>
@endsection