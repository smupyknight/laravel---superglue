@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		@if(count($powerups))
			@foreach($powerups as $powerup)
				<div class="col-lg-4">
                    <div class="widget white-bg p-xl">
                        <h2>
                        	<a href="/powerups/view/{{ $powerup->id }}" style="color: inherit;">{{ $powerup->title }}</a>
                        </h2>
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
			@endforeach
		@else
			<p>No Powerups found.</p>
		@endif
	</div>
	<div class="row text-center">
		{!! $powerups->render() !!}
	</div>
</div>
@endsection