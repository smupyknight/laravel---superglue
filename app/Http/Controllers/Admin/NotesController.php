<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Note;
use Auth;

class NotesController extends Controller
{

	/**
	 * Ajax call to add notes to account
	 * @param  Request $request
	 * @return null
	 */
	public function postAdd(Request $request)
	{
		$this->validate($request, ['note' => 'required']);
		$note = new Note;
		$note->account_id = $request->acc_id;
		$note->user_id = Auth::user()->id;
		$note->content = $request->note;
		$note->save();
	}

	/**
	 * Update notes to account
	 * @param  Request $request
	 * @param  int  $account_id
	 * @return null
	 */
	public function postEdit(Request $request,$note_id)
	{
		$this->validate($request, ['note' => 'required']);
		Note::where('id', '=', $note_id)->update(['content' => $request->note]);
	}

	public function postDelete($note_id)
	{
		Note::where('id', $note_id)->delete();
	}

}
