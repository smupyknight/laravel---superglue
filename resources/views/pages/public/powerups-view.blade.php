@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
            <div class="widget white-bg p-xxl">
                <h2>
                	<a href="/powerups/view/{{ $powerup->id }}" style="color: inherit;">{{ $powerup->title }}</a>
                </h2>
                <hr>
                @if ($powerup->image)
                    <img alt="image" class="img img-responsive" src="{{ asset('storage/powerups/'.$powerup->image) }}">
                @endif
                <ul class="list-unstyled m-t-md">
                    <li>
                        <label>Code:</label>
                        {{ $powerup->coupon_code }}
                    </li>
                    <li>
                        <label>Description:</label>
                        {!! $powerup->description !!}
                    </li>
                    <li>
                        <label>Link:</label>
                        <a href="{{ $powerup->link }}">{{ $powerup->link }}</a>
                    </li>
                </ul>
            </div>
        </div>
	</div>
</div>
@endsection
