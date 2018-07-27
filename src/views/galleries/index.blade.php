@extends('admin.layouts.master')

@section('title', 'List Galleries')

@section('page-title', 'List Galleries')

@section('breadcrumb')
<li><a href="{{ route('galleries.index') }}">Gallery</a></li>
<li class="active">All Gallery</a></li>
@endsection

@section('content')

<div class="row">
    <div class="col-xs-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">List of all gallery</h3>
            </div>

            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th class="col-sm-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($galleries as $gallery)
                            <tr>
                                <td>{{ $gallery->title }} ({{ $gallery->photos()->count() }})</td>
                                <td>{!! $gallery->statusString() !!}</td>
                                <td class="text-center">
                                    {!! Form::open(['route' => ['galleries.destroy', $gallery->id], 'method' => 'DELETE', 'data-confirm' => 'Are you sure you want to delete this gallery? All the photos in this gallery will be deleted.']) !!}
                                        <a data-toggle="tooltip" title="Edit" href="{{ route('galleries.edit', $gallery->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                        <button data-toggle="tooltip" title="Delete" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $(".table").DataTable();
    });
</script>
@endsection