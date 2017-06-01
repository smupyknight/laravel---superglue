<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PowerUp;

class PowerUpsController extends Controller
{

	public function getIndex()
	{
		$powerups = PowerUp::paginate(10);
		foreach ($powerups as $powerup) {
			$powerup->image = $powerup->image ? asset('storage/powerups/'.$powerup->image) : '';
		}

		return response()->json($powerups);
	}

}
