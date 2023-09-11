<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class PasswordController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;

	protected $redirectPath='/';

	protected $subject='Востановление пароля на сайте allfor2.com';

	/**
	 * Create a new password controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard $auth
	 * @param  \Illuminate\Contracts\Auth\PasswordBroker $passwords
	 */
	public function __construct(Guard $auth, PasswordBroker $passwords)
	{
		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');
	}

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postEmail(Request $request)
	{
		$this->validate($request, ['email' => 'required|email']);

		$response = $this->passwords->sendResetLink($request->only('email'), function($m)
		{
			$m->subject($this->getEmailSubject());
		});

		switch ($response)
		{
			case PasswordBroker::RESET_LINK_SENT:
				return redirect()->back()->with('status', trans($response));

			case PasswordBroker::INVALID_USER:
				return redirect()->back()->withErrors(['email' => trans($response)]);
		}
	}
	/**
	 * Reset the given user's password.
	 *
	 * @param  Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postReset(Request $request)
	{
		$this->validate($request, [
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed',
		]);

		$credentials = $request->only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = $this->passwords->reset($credentials, function($user, $password)
		{
			$user->password = bcrypt($password);

			$user->save();

			if($user->isActive) {
				$this->auth->login($user);
			}
		});

		switch ($response)
		{
			case PasswordBroker::PASSWORD_RESET:
				return redirect($this->redirectPath())
					->with('message',trans($response));

			default:
				return redirect()->back()
					->withInput($request->only('email'))
					->withErrors(['email' => trans($response)]);
		}
	}

}
