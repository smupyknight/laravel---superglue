<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Community;

class CommunitiesController extends Controller
{

	/**
	 * Show communities list page
	 * @return view
	 */
	public function getIndex()
	{
		$communities = Community::orderBy('id', 'desc')->paginate(10);

		return view('pages.communities-list')
		     ->with('communities', $communities)
		     ->with('title', 'Communities List');
	}

	/**
	 * Show create community page
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.communities-create')
		     ->with('title', 'Create Community');
	}

	/**
	 * Handle create community data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'community_name' => 'required|unique:communities,name',
		]);

		$community = new Community;
		$community->name = $request->community_name;
		$community->save();

		return redirect('/communities');
	}

	/**
	 * Show community page
	 * @param  int $community_id
	 * @return view
	 */
	public function getView($community_id)
	{
		$community = Community::findOrFail($community_id);

		return view('pages.communities-view')
		     ->with('community', $community)
		     ->with('title', 'View Community');
	}

	/**
	 * Show edit community page
	 * @param  int $community_id
	 * @return view
	 */
	public function getEdit($community_id)
	{
		$community = Community::findOrFail($community_id);

		return view('pages.communities-edit')
		     ->with('community', $community)
		     ->with('title', 'Edit Community');
	}

	/**
	 * Handle edit community data
	 * @param  Request $request
	 * @param  int     $community_id
	 * @return redirect
	 */
	public function postEdit(Request $request,$community_id)
	{
		$this->validate($request, [
			'community_name' => 'required|unique:communities,name,'.$community_id,
		]);

		$community = Community::findOrFail($community_id);
		$community->name = $request->community_name;
		$community->save();

		return redirect('/communities');
	}

	/**
	 * Delete community
	 * @param  int $community_id
	 * @return redirect
	 */
	public function getDelete($community_id)
	{
		$community = Community::findOrFail($community_id);
		$community->delete();

		return redirect('/communities');
	}

}
