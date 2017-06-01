@extends('layouts.default')
@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ $title or '' }}</h5>

                        <div class="ibox-tools">
                            <a href="/admin/offices/edit/{{ $office->id }}" class="btn btn-default btn-xs">Edit Office</a>
                        </div>

                    </div>
                    <div class="ibox-content">
                        <div class="form-horizontal">

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Name :</label>
                                <div class="col-lg-4">
                                    <p class="form-control-static"> {{ $office->name }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Features :</label>
                                <div class="col-lg-10">
                                    <p class="form-control-static"> {{ $office->features }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Capacity :</label>
                                <div class="col-lg-10">
                                    <p class="form-control-static"> {{ $office->capacity }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Length :</label>
                                <div class="col-lg-4">
                                    <p class="form-control-static"> {{ $office->length }}</p>
                                </div>
                                <label class="col-lg-2 control-label">Width :</label>
                                <div class="col-lg-4">
                                    <p class="form-control-static"> {{ $office->width }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">SignUp Fee :</label>
                                <div class="col-lg-4">
                                    <p class="form-control-static"> ${{ $office->signup_fee }}</p>
                                </div>
                                <label class="col-lg-2 control-label">Cost :</label>
                                <div class="col-lg-4">
                                    <p class="form-control-static"> ${{ $office->cost }}/ fortnight</p>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <h3> Images</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <button data-toggle="modal" data-target="#add-image-modal" class="btn btn-default btn-sm pull-right m-y-10">Add Image</button>
                            </div>
                        </div>
                        <div class="row">
                            @if(count($office->images))
                                @foreach($office->images as $image)
                                    <div class="col-md-3">
                                        <img src="/storage/office_images/{{$image->file}}" class="img img-responsive" alt="{{$image->name}}" title="{{$image->name}}" style="padding:10px">
                                        <p class="text-center">{{$image->name}}</p>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-lg-10 ">
                                    <p>No image have been uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

        </div>
    </div>
    <div id="add-image-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form id="add-image-form" action="/admin/offices/add-image" method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Image</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">Select File</label>
                                <input type="file" name="selected_file" class="form-control">
                            </div>
                        </div>
                        <input type="hidden" name="office_id" value="{{ $office->id }}">
                        {{ csrf_field() }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#add-image-form').on('submit', function(event) {
            event.preventDefault();
            $('.modal-footer button').removeAttr('disabled');
            $.ajax({
                type: 'post',
                url:  $(this).attr('action'),
                processData: false,
                contentType: false,
                data: new FormData($(this).get(0)),
                success: function(data, status, jqxhr) {
                    window.location.reload();
                },
                error: function(jqxhr, status, error) {
                    var errors = [];
                    if (jqxhr.status == 422) {
                        for (var field in jqxhr.responseJSON) {
                            $.merge(errors, jqxhr.responseJSON[field]);
                        }
                    } else {
                        errors = [error];
                    }
                    $('.modal-body').append($('<div class="alert alert-danger"></div>').html(errors.join('<br>')));
                    $('.modal-footer button').removeAttr('disabled');
                }
            });
        });
    </script>
@endsection