<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;

class TestController extends Controller
{

	public function postIndex()
	{
		return response()->json([
			'user_id' => Auth::guard('api')->id(),
		]);
	}

}
