<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Like;

class LikesController extends Controller
{

	/**
	 * Get likes for post
	 * @param  int $post_id
	 * @return json
	 */
	public function getList($post_id)
	{
		$likes = Like::where('post_id', $post_id)->get();
		$result = [];

		foreach ($likes as $like) {
			$result[] = [
				'user_id' => $like->user_id,
				'user_name' => ucwords($like->user->first_name.' '.$like->user->last_name),
			];
		}

		return response()->json($result);
	}

	/**
	 * Add like to post. Also increments value on num_likes for posts table
	 * @param  Request $request
	 * @param  int  $post_id
	 * @return json
	 */
	public function postCreate(Request $request, $post_id)
	{
		$request->merge([
			'post_id' => $post_id,
			'user_id' => $this->user->id,
		]);

		$this->validate($request, [
			'post_id' => 'required|exists:posts,id',
			'user_id' => 'required|unique:likes,user_id,NULL,id,post_id,'.$post_id,
		], [
			'post_id.exists' => 'The post does not exist in the database.',
			'user_id.unique' => 'User has already liked the post.',
		]);

		$like = new Like;
		$like->user_id = $this->user->id;
		$like->post_id = $post_id;
		$like->save();

		$like->post->increment('num_likes');
	}

	/**
	 * Remove like from post. Also decrements value on num_likes for posts table
	 * @param  Request $request
	 * @param  int  $post_id
	 * @return json
	 */
	public function postDelete(Request $request, $post_id)
	{
		$this->validate($request, [
			'post_id' => 'required|exists:likes,post_id,user_id,'.$this->user->id,
			'user_id' => 'required|exists:likes,user_id,post_id,'.$post_id,
		], [
			'exists' => 'The :attribute does not exist in the database.',
		]);

		$like = Like::where('user_id', $this->user->id)->where('post_id', $post_id)->firstOrFail();
		$like->post->decrement('num_likes');
		$like->delete();

		return response()->json(['message' => 'Delete successful.']);
	}

}
