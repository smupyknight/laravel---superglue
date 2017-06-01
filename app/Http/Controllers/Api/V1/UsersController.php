<?php

namespace App\Http\Controllers\Api\V1;

use App\Account;
use App\Device;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Invitation;
use App\ReferFriend;
use App\Space;
use App\User;
use Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => [
			'postCreate',
			'getInvite',
		]]);
	}

	public function getList()
	{
		$users = User::all();

		foreach ($users as $user) {
			$user->avatar = $user->getAvatarUrl();
		}

		return response()->json($users);
	}

	public function getInvite($invitation_token = null)
	{
		if (!$invitation_token) {
			return response()->json(['Error' => 'No token provided']);
		}

		$invitation = Invitation::whereToken($invitation_token)->first();

		if (!$invitation) {
			return response()->json(['Error' => 'No invitation found']);
		} else {
			return response()->json($invitation->user);
		}
	}

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'first_name'    => 'required',
			'last_name'     => 'required',
			'phone'         => 'numeric',
			'dob'           => 'date_format:Y-m-d',
			'like_tour'     => 'boolean',
			'has_visited'   => 'boolean',
			'accepts_terms' => 'required|boolean',
			'is_public'     => 'boolean',
			'postcode'      => 'digits:4',
			'timezone'      => 'required',
			'password'      => 'required_without:linkedin_token|confirmed',
			'email'         => 'required|email|unique:users',
			'space_id'      => 'required|numeric',
		]);

		$user = new User;

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->salutation = $request->input('salutation', '');
		$user->email = $request->email;
		$user->dob = $request->dob;
		$user->linkedin_token = $request->input('linkedin_token', '');
		$user->company_name = $request->input('company_name', '');
		$user->job_title = $request->input('job_title', '');
		$user->industry = $request->input('industry', '');
		$user->phone = $request->input('phone', '');
		$user->address = $request->input('address', '');
		$user->city = $request->input('city', '');
		$user->suburb = $request->input('suburb', '');
		$user->postcode = $request->input('postcode', '');
		$user->state = $request->input('state', '');
		$user->country = $request->input('country', '');
		$user->twitter_handle = $request->input('twitter_handle', '');
		$user->instagram_handle = $request->input('instagram_handle', '');
		$user->bio = $request->input('bio', '');
		$user->type = $request->input('type', 'Member');
		$user->accepts_terms = $request->input('accepts_terms', '0');
		$user->is_public = $request->input('is_public', '0');
		$user->like_tour = $request->input('like_tour', '0');
		$user->has_visited = $request->input('has_visited', '0');
		$user->website = $request->input('website', '0');
		$user->password = bcrypt($request->password);
		$user->timezone = $request->input('timezone', 'Australia/Brisbane');

		$account = new Account;
		$account->name = $request->first_name . ' ' . $request->last_name;
		$account->email = $request->email;
		$account->space_id = $request->space_id;
		$account->save();

		$user->account_id = $account->id;
		$user->save();

		$this->addTimeline([
			'created_by' => null,
			'user_id'    => $user->id,
			'account_id' => $user->account_id,
			'title'      => 'User Signup',
			'message'    => ucwords($request->first_name.' '.$request->last_name).' ('.$request->email.') signed up.',
			'type'       => 'info',
		]);

		return response()->json(['user_id' => $user->id]);
	}

	public function postEdit(Request $request, $user_id)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|email|unique:users,email,'.$this->user->id,
			'dob'        => 'date_format:Y-m-d',
		]);

		$user = User::findOrFail($user_id);

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->dob = $request->dob;
		$user->company_name = $request->get('company_name', '');
		$user->job_title = $request->get('job_title', '');
		$user->industry = $request->get('industry', '');
		$user->phone = $request->get('phone', '');
		$user->address = $request->get('address', '');
		$user->twitter_handle = $request->get('twitter_handle', '');
		$user->instagram_handle = $request->get('instagram_handle', '');
		$user->bio = $request->get('bio', '');
		$user->security_card_number = $request->get('security_card_number', '');

		if ($request->password) {
			$user->password = bcrypt($request->password);
		}

		$user->timezone = 'Australia/Brisbane';
		$user->save();
	}

	public function getReferFriends(Request $request)
	{
		$this->validate($request, [
			'name'    => 'required',
			'email'   => 'required',
			'phone'   => 'required',
			'message' => 'required'
		]);

		$refer_friend = new ReferFriend;
		$refer_friend->user_id = Auth::guard('api')->user()->id;
		$refer_friend->name = $request->get('name', '');
		$refer_friend->email = $request->get('email', '');
		$refer_friend->phone = $request->get('phone', '');
		$refer_friend->message = $request->get('message', '');
		$refer_friend->save();

		return response()->json('success');
	}

	public function postRegisterDevice(Request $request)
	{
		$this->validate($request, [
			'name'  => 'required',
			'token' => 'required'
		]);

		$device = Device::whereToken($request->token)->first();

		if (!$device) {
			$device = new Device;
			$device->user_id = Auth::guard('api')->user()->id;
			$device->name = $request->name;
			$device->token = $request->token;
			$device->save();
		}

		return response()->json($device);
	}

}
