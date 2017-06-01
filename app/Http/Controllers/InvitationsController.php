<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Hash;
use Auth;
use App\User;
use App\Industry;
use Carbon\Carbon;

class InvitationsController extends Controller
{

	/**
	 * Display Dashboard
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getAccept($token)
	{
		$invitation = Invitation::whereToken($token)->firstOrFail();
		$user = User::findOrFail($invitation->user_id);

		$industries = Industry::$industries;

		return view('auth.invitations-accept')
		     ->with('invitation', $invitation)
		     ->with('industries', $industries)
		     ->with('user', $user)
		     ->with('title', 'Accept Invitation');
	}

	public function postAccept(Request $request, $token)
	{
		$invitation = Invitation::whereToken($token)->firstOrFail();

		$this->validate($request, [
			'first_name'            => 'required',
			'last_name'             => 'required',
			'phone'                 => 'required',
			'company_name'          => 'required',
			'job_title'             => 'required',
			'password'              => 'required|confirmed',
			'password_confirmation' => 'required',
			'postcode'              => 'required|numeric',
			'industry'              => 'required',
		]);

		$user = User::find($invitation->user_id);

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->password = bcrypt($request->password);
		$user->postcode = $request->get('postcode', '');
		$user->job_title = $request->get('job_title', '');
		$user->company_name = $request->get('company_name', '');
		$user->industry = $request->get('industry', '');
		$user->phone = $request->get('phone', '');
		$user->twitter_handle = $request->get('twitter_handle', '');
		$user->instagram_handle = $request->get('instagram_handle', '');
		$user->bio = $request->get('bio', '');
		$user->timezone = $request->get('timezone', 'Australia/Brisbane');

		$user->save();

		$this->addTimeline([
			'created_by' => Auth::user() ? Auth::user()->id : null,
			'user_id'    => $user->id,
			'account_id' => $user->account,
			'message'    => 'User completed invite: '.ucwords($request->first_name.' '.$request->last_name).' ('.$user->email.')',
			'type'       => 'info'
		]);

		if ($request->hasfile('avatar')) {
			$user->setAvatar($request->file('avatar'));
		}

		$invitation->delete();

		Auth::login($user);

		return redirect('/');
	}

}
