@extends('layouts.public')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Walkthrough Video</h5>
                </div>
                <div class="ibox-content">
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/188246492"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
