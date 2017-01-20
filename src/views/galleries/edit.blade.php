@extends('admin.layouts.master')

@section('title', 'Edit Gallery')

@section('page-title', 'Edit Gallery')

@section('breadcrumb')
<li><a href="{{ route('galleries.index') }}">Galleries</a></li>
<li class="active">Edit Gallery</a></li>
@endsection

@section('content')

<div class="row">
    {!! Form::model($gallery, ['route' => ['galleries.update', $gallery->id], 'method' => 'PATCH', 'class' => 'gallery-form']) !!}
        <div class="col-sm-12 col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit gallery</h3>
                </div>
                
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter title here']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! Form::submit('Save', ['class' => 'btn btn-info pull-right']) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Publish</h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('status', 'Status', ['class' => 'col-md-2 contol-label']) !!}

                        <div class="col-md-10">
                            <label class="radio-inline">
                                {!! Form::radio('status', 1) !!} Publish
                            </label>
                            <label class="radio-inline">
                                {!! Form::radio('status', 0) !!} Unpublish
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

</div>

<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add photos</h3>
            </div>
            
            <div class="box-body">
                {!! Form::open(['route' => 'photos.store', 'class' => 'dropzone', 'id' => 'my-dropzone', 'files' => true]) !!}
                    {!! Form::hidden('gallery_id', $gallery->id) !!}
                    <div class="dz-message" data-dz-message>
						<span>Drop files here or click to upload.</span> <i class="fa fa-hand-o-down"></i>
					</div>
                    <div class="fallback">
                        <input name="file" type="file" multiple />
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.gallery-form').validate({
            rules: {
                title: 'required'
            }
        });
    });

Dropzone.options.myDropzone = {
    acceptedFiles: 'image/*',
    maxFilesize: 2,
    addRemoveLinks: true,
    dictRemoveFile: 'Remove',

    init: function() {
        var myDropzone = this;

        $.get('{{ route('photos', $gallery->id) }}', function(data) {
            $.each(data.photos, function (key, value) {
                var file = {name: value.server, size: value.size};
                myDropzone.emit("addedfile", file);
                myDropzone.emit("thumbnail", file, value.imageurl);
                myDropzone.createThumbnailFromUrl(file, value.imageurl);
                myDropzone.emit("complete", file);
            });
        });

        this.on("removedfile", function(file) {
            $.ajax({
                type: 'POST',
                url: '{{ route('photos.delete') }}',
                data: {filename: file.name},
                dataType: 'json'
            });
        });
    }
};
</script>
@endsection