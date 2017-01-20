@extends('admin.layouts.master')

@section('title', 'Add Gallery')

@section('page-title', 'Add Gallery')

@section('breadcrumb')
<li><a href="{{ route('galleries.index') }}">Gallery</a></li>
<li class="active">Add Gallery</a></li>
@endsection

@section('content')

<div class="row">
    {!! Form::open(['route' => 'galleries.store', 'class' => 'gallery-form']) !!}
        <div class="col-sm-12 col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add gallery</h3>
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
                                {!! Form::radio('status', 1, true) !!} Publish
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
</script>
@endsection