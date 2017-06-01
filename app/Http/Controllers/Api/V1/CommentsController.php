<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Comment;
use App\User;

class CommentsController extends Controller
{

	/**
	 * Get list for comments on post
	 * @param  int $post_id
	 * @return json
	 */
	public function getList($post_id)
	{
		$comments = Comment::where('post_id', $post_id)->get();
		$result = [];

		foreach ($comments as $comment) {
			$result[] = [
				'comment_id' => $comment->id,
				'user_id' => $comment->user_id,
				'user_name' => ucwords($comment->user->first_name.' '.$comment->user->last_name),
				'content' => $comment->content,
				'created_at' => $comment->created_at,
			];
		}

		return response()->json($result);
	}

	/**
	 * Create comment for post
	 * @param  Request $request
	 * @param  int  $post_id
	 * @return json
	 */
	public function postCreate(Request $request, $post_id)
	{
		$request->merge(['post_id' => $post_id]);

		$this->validate($request, [
			'post_id' => 'required|exists:posts,id',
			'content' => 'required',
		], [
			'post_id.exists' => 'The post does not exist in the database.',
		]);

		$comment = new Comment;
		$comment->user_id = $this->user->id;
		$comment->post_id = $post_id;
		$comment->content = $request->content;
		$comment->save();

		return response()->json(['comment_id' => $comment->id]);
	}

	/**
	 * Updates comment from post
	 * @param  Request $request
	 * @param  int  $comment_id
	 * @return json
	 */
	public function postUpdate(Request $request, $comment_id)
	{
		$this->validate($request, [
			'content' => 'required',
		]);

		$comment = Comment::findOrFail($comment_id);
		$comment->content = $request->content;
		$comment->save();
	}

	/**
	 * Delete comment from post
	 * @param  Request $request
	 * @return json
	 */
	public function postDelete(Request $request,$comment_id)
	{
		$user = User::where('api_token', $request->api_token)->first();
		Comment::where('id', $comment_id)->where('user_id', $user->id)->delete();

		return response()->json([
			'message' => 'Delete successful.',
		]);
	}

}
