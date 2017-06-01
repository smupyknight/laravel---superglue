<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;
use Exception;
use Log;

class Invitation extends Model
{

	public function send()
	{
		$data = [
			'invitation' => $this,
		];

		try {
			Mail::send('emails.invitations-email', $data, function ($mail) {
				$mail->from('noreply@littletokyotwo.com');
				$mail->to($this->user->email);
				$mail->subject('Invitation to join Superglue');
			});
		} catch (Exception $e) {
			Log::info('Sending invite email error : '.$e->getMessage());
		}
	}

	/**
	 * Get the user record associated with the invitation.
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
