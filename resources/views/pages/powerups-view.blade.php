@extends('layouts.default')
@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ $title or '' }}</h5>

                        <div class="ibox-tools">
                            <a href="/admin/powerups/edit/{{ $powerup->id }}" class="btn btn-default btn-xs">Edit Powerup</a>
                        </div>

                    </div>
                    <div class="ibox-content">
                        <div class="form-horizontal">

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Title :</label>
                                <div class="col-lg-4">
                                    <p class="form-control-static"> {{ $powerup->title }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Description :</label>
                                <div class="col-lg-4">
                                    <p class="form-control-static"> {!! $powerup->description !!}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Link :</label>
                                <div class="col-lg-4">
                                    <p class="form-control-static"> {{ $powerup->link }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Image :</label>
                                <div class="col-lg-4">
                                    @if ($powerup->image)
                                    <img src="{{ asset('storage/powerups/'.$powerup->image) }}" class="img-responsive" style="max-width:250px">
                                    @else
                                    <p class="form-control-static">none</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection