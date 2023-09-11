@extends('admin.layout.default')

@section('content')
    @include('admin.menu.menuCategoryScripts')

    <div class="container">
        <div>Upload images</div>
        <!-- The fileinput-button span is used to style the file input field as button -->
    <span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
        <br>
        <br>
        <!-- The global progress bar -->
        <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
        </div>
        <!-- The container for the uploaded files -->
        <div id="files" class="files radio-inputs without-select">
            {{-- */ $i = 0; /* --}}
            @foreach($fileNames as $key => $file)
                {{-- */ $id = str_replace('.','separator', $key); /* --}}
                <div class="menu-image-preview-block col-md-3 col-sm-12" id="block-{{ $id }}">
                    <img src="{{ $file }}" class="menu-image-preview">
                    <input type="radio"
                           id="image_radio{{ $i }}"
                           name="image_radio"
                           class="menu-image-preview-radio"
                           value="{{ $file }}">
                    <label for="image_radio{{ $i }}"><span></span></label>
                    <button type="button" class="delete-btn" data-owner-id="{{ $id }}" data-url="{{ url('administrator/deleteImage') }}"></button>
                </div>
                {{-- */ $i++ /* --}}
            @endforeach
        </div>
    </div>
@endsection