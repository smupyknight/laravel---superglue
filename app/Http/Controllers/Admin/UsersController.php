<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Industry;
use App\Invitation;
use App\Space;
use App\Timeline;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use App\MentorSchedule;

class UsersController extends Controller
{

	/**
	 * Show list of all Users with and without filters.
	 * @param  Request $request [description]
	 * @return [type] [description]
	 */
	public function getIndex(Request $request)
	{
		$query = User::query();

		if ($request->search) {
			$query->where( function($query)	use ($request) {
				$query->orWhere('email', 'like', '%' . $request->search . '%');
				$query->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $request->search . '%']);
			});
		}

		if ($request->type && $request->type != 'All') {
			$query->where('type', $request->type);
		}

		if ($request->showDeleted == 1) {
			$query->onlyTrashed();
		}

		$users = $query->orderBy('id', 'desc')->paginate(25);

		return view('pages.users-list')
		     ->with('users', $users)
		     ->with('title', 'User List');
	}

	/**

	 * Show create user form
	 * @return view
	 */
	public function getCreate()
	{
		$industries = Industry::$industries;
		$accounts = Account::all();
		$spaces = Space::all();

		return view('pages.users-create')
		     ->with('accounts', $accounts)
		     ->with('industries', $industries)
		     ->with('spaces', $spaces);
	}

	/**
	 * Show user invite page
	 * @return view
	 */
	public function getInvite()
	{
		$accounts = Account::orderBy('name', 'asc')->get();

		return view('pages.users-invite')
		     ->with('accounts', $accounts)
		     ->with('title', 'Invite User');
	}

	/**
	 * Handle user invite data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postInvite(Request $request)
	{
		$this->validate($request, [
			'first_name'       => 'required|max:255',
			'last_name'        => 'required|max:255',
			'email'            => 'required|email|max:255|unique:users',
			'account_id'       => 'required',
		]);

		$user = new User;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->is_account_admin = (int) $request->is_account_admin;
		$user->account_id = $request->account_id;
		$user->timezone = 'Australia/Brisbane';
		$user->save();

		$this->addTimeline([
			'created_by' => $this->user->id,
			'user_id'    => $user->id,
			'account_id' => $request->account_id,
			'title'      => 'Invited User',
			'message'    => 'Invited user '.ucwords($request->first_name.' '.$request->last_name).' ('.$request->email.')',
			'type'       => 'info',
		]);

		$invitation = new Invitation;
		$invitation->token = substr(md5(microtime()), 0, 10);
		$invitation->user_id = $user->id;
		$invitation->save();

		$invitation->send();

		return redirect('/admin/users');
	}

	/**
	 * Ajax call to resend invite email
	 * @param  Request $request
	 * @return null
	 */
	public function postResendInvite(Request $request)
	{
		$user = User::findOrFail($request->user_id);

		if ($invitation = $user->invitations()->first()) {
			$invitation->touch();
			$invitation->send();

			$this->addTimeline([
				'created_by' => $this->user->id,
				'user_id'    => $user->id,
				'title'      => 'Re-sent invitation email',
				'message'    => 'Re-sent invitation email to: '.$user->email,
				'type'       => 'info',
			]);
		} else {
			$invitation = new Invitation;
			$invitation->token = substr(md5(microtime()), 0, 10);
			$invitation->user_id = $user->id;
			$invitation->save();

			$invitation->send();

			$this->addTimeline([
				'created_by' => $this->user->id,
				'user_id'    => $user->id,
				'title'      => 'Sent invitation email',
				'message'    => 'Sent invitation email to: '. $user->email,
				'type'       => 'info',
			]);
		}
	}

	/**
	 * Show Sign Up Page
	 * @return view
	 */
	public function getSignUp()
	{
		return view('pages.public.users-signup');
	}

	/**
	 * Show user profile page
	 * @param  int $user_id
	 * @return view
	 */
	public function getView($user_id)
	{
		$user = User::findOrFail($user_id);

		$title = $user->name . ' Profile';

		return view('pages.users-view')
		     ->with('user', $user)
		     ->with('title', $title);
	}

	/**
	 * Show edit user page
	 * @param  int $user_id
	 * @return view
	 */
	public function getEdit($user_id)
	{
		$user = User::findOrFail($user_id);
		$accounts = Account::all();
		$industries = Industry::$industries;

		return view('pages.users-edit')
		     ->with('user', $user)
		     ->with('accounts', $accounts)
		     ->with('industries', $industries)
		     ->with('title', 'Edit User');
	}

	/**
	 * Handle edit user data
	 * @param  Request $request
	 * @param  int  $user_id
	 * @return redirect
	 */
	public function postEdit(Request $request,$user_id)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|email|unique:users,email,'.$user_id,
			'dob' => 'date_format:d/m/Y'
		]);

		$dob = $request->dob ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null;

		$user = User::findOrFail($user_id);

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->account_id = $request->account_id;
		$user->type = $request->type;
		$user->is_account_admin = (int) $request->is_account_admin;
		$user->dob = $dob;
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

		return redirect('/admin/users');
	}

	/**
	 * Handle saving of user data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'first_name'    => 'required',
			'last_name'     => 'required',
			'phone'         => '',
			'company_name'  => '',
			'job_title'     => '',
			'dob'           => 'date_format:d/m/Y',
			'like_tour'     => 'boolean',
			'has_visited'   => 'boolean',
			'accepts_terms' => 'boolean',
			'password'      => 'confirmed',
			'email'         => 'required|email',
		]);

		$dob = $request->dob ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null;

		$user = new User;

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->dob = $dob;
		$user->linkedin_token = $request->get('linkedin_token', '');
		$user->company_name = $request->get('company_name', '');
		$user->job_title = $request->get('job_title', '');
		$user->industry = $request->get('industry', '');
		$user->phone = $request->get('phone', '');
		$user->address = $request->get('address', '');
		$user->twitter_handle = $request->get('twitter_handle', '');
		$user->instagram_handle = $request->get('instagram_handle', '');
		$user->bio = $request->get('bio', '');
		$user->type = $request->get('type', 'Member');
		$user->is_account_admin = (int) $request->is_account_admin;
		$user->accepts_terms = $request->get('accepts_terms', '0');
		$user->like_tour = $request->get('like_tour', '0');
		$user->has_visited = $request->get('has_visited', '0');
		$user->timezone = $request->get('timezone', 'Australia/Brisbane');
		$user->security_card_number = $request->get('security_card_number', '');

		if ($request->password) {
			$user->password = bcrypt($request->password);
		}

		if ($request->account_id) {
			$user->account_id = $request->account_id;
		} else {
			$account = new Account;
			$account->name = $request->first_name . ' ' . $request->last_name;
			$account->email = $request->email;
			$account->space_id = $request->get('space_id', Space::orderBy('id', 'ASC')->first()->id);
			$account->save();

			$user->account_id = $account->id;
		}

		$user->save();

		$this->addTimeline([
			'created_by' => $this->user->id,
			'user_id'    => $user->id,
			'account_id' => $user->account_id,
			'title'      => 'User Signup',
			'message'    => ucwords($request->first_name.' '.$request->last_name).' ('.$request->email.') signed up.',
			'type'       => 'info',
		]);

		return redirect('/admin/users');
	}

	/**
	 * Handle delete user data
	 * @param  int  $user_id
	 * @return redirect
	 */
	public function postDelete($user_id)
	{
		$user = User::findOrFail($user_id);

		if ($user->type == 'Mentor') {
			MentorSchedule::where('mentor_id', $user->id)->delete();
		}

		$user->delete();
	}

	/**
	 * Handle restoring of user account
	 * @param  int  $user_id
	 * @return redirect
	 */
	public function getRestore($user_id)
	{
		$user = User::withTrashed()->findOrFail($user_id);

		$user->restore();

		return redirect('/admin/users');
	}

	public function getLoadTimeline(Request $request)
	{
		$result = ['status' => 1, 'message' => 'Feeds fetched.', 'feeds' => []];
		$offset = isset($request->offset) ? $request->offset : 0;

		$feeds = Timeline::whereUserId($request->user_id)->limit(10)->offset($offset)->get();

		if (!count($feeds)) {
			$result = ['status' => 0, 'message' => 'No feeds found.'];
			return $result;
		}

		foreach ($feeds as $timeline) {
			$result['feeds'][] = [
				'age'        => $timeline->created_at->diffForHumans(),
				'user_name'  => $timeline->user->first_name . ' ' . $timeline->user->last_name,
				'title'      => $timeline->title,
				'author'     => $timeline->author->first_name . ' ' . $timeline->author->last_name,
				'created_at' => $timeline->created_at->format('h:i a - d.m.Y'),
				'message'    => $timeline->message
			];
		}

		return response()->json($result);
	}

	public function getTypeahead($phrase)
	{
		$users = User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$phrase . '%'])
		             ->orderBy('first_name')
		             ->orderBy('last_name')
		             ->take(10)
		             ->get(['id', 'account_id', 'first_name', 'last_name', 'email']);

		return response()->json($users);
	}

}
