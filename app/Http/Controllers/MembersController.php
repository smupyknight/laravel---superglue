<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Industry;

class MembersController extends Controller
{

	public function getIndex(Request $request)
	{
		$query = User::orderBy('id', 'desc');

		$query->where('type', 'Member');

		$query->where(function($query) use($request)
		{
			$query->orWhere('first_name', 'like', '%'.$request->global_search.'%');
			$query->orWhere('last_name', 'like', '%'.$request->global_search.'%');
			$query->orWhere('email', 'like', '%'.$request->global_search.'%');
		});

		if ($request->industry) {
			$query->where('industry', $request->industry);
		}

		$members = $query->paginate(25);

		$industries = Industry::$industries;

		return view('pages.public.members-list')
				->with('members', $members)
				->with('industries', $industries)
				->with('title', 'Members List');
	}

}
