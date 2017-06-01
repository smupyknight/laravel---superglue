<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Auth;
use App\Timeline;

class Controller extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	protected $user = null;

	public function __construct()
	{
		$this->user = request()->api_token ? Auth::guard('api')->user() : Auth::user();
	}

	/**
	 * Create timeline entry
	 */
	public function addTimeline(array $info)
	{
		$info = collect($info);

		$timeline = new Timeline;

		$timeline->created_by = $info->get('created_by', '');
		$timeline->user_id = $info->get('user_id', '');
		$timeline->account_id = $info->get('account_id', '');
		$timeline->title = $info->get('title', '');
		$timeline->message = $info->get('message', '');
		$timeline->type = $info->get('type', '');

		$timeline->save();
	}

}
