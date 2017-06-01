<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;

class PostsController extends Controller
{

	/**
	 * Get posts for feed
	 * @param  int $feed_id
	 * @return json
	 */
	public function getList($feed_id)
	{
		$posts = Post::where('feed_id', $feed_id)->paginate(10);

		return response()->json($posts);
	}

	/**
	 * Create post for feed
	 * @param  Request $request
	 * @param  int     $feed_id
	 * @return object
	 */
	public function postCreate(Request $request, $feed_id)
	{
		$request->merge(['feed_id' => $feed_id]);

		$this->validate($request, [
			'feed_id' => 'required|exists:feeds,id',
			'content' => 'required',
		], [
			'feed_id.exists' => 'The feed ID does not exist in the database.',
		]);

		$post = new Post;
		$post->created_by = $this->user->id;
		$post->feed_id = $feed_id;
		$post->unique_id = md5(microtime());
		$post->content = $request->content;
		$post->save();

		return $post;
	}

}
