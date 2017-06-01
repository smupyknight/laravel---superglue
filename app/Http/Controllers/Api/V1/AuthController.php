<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use App\Device;
use App\User;
use Hash;
use Str;

class AuthController extends Controller
{

	public function postAuthenticate(Request $request)
	{
		$this->validate($request, [
			'email'    => 'required',
			'password' => 'required',
		]);

		$user = User::whereEmail($request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			abort(401);
		}

		if ($user->api_token == '') {
			$user->api_token = md5(microtime());
			$user->save();
		}

		return response()->json(['token' => $user->api_token]);
	}

	public function postForgotPassword(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
		]);

		$response = Password::sendResetLink(
			$request->only('email'),
			function (Message $message) {
				$message->subject('Your Password Reset Link');
			}
		);

		if ($response != Password::RESET_LINK_SENT) {
			return response()->json(['error' => trans($response)]);
		}

		return response()->json(['status' => trans($response)]);
	}

	public function postResetPassword(Request $request)
	{
		$this->validate($request, [
			'token'    => 'required',
			'email'    => 'required|email',
			'password' => 'required|confirmed|min:6',
		]);

		$credentials = $request->only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function ($user, $password) {
			$user->forceFill([
				'password' => bcrypt($password),
				'remember_token' => Str::random(60),
			])->save();
		});

		if ($response != Password::PASSWORD_RESET) {
			return response()->json(['error' => trans($response)]);
		}

		return response()->json(['status' => trans($response)]);
	}

	public function postLinkedin(Request $request)
	{
		$this->validate($request, [
			'access_token' => 'required'
		]);

		$user = User::whereLinkedinToken($request->access_token)->firstOrFail();

		if (!$user->api_token) {
			$user->api_token = md5(microtime());
			$user->save();
		}

		return response()->json(['token' => $user->api_token]);
	}

}
