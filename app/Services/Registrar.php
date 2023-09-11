<?php namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;

class Registrar implements RegistrarContract {

	protected $id;

	protected $activationCode;

	protected $username;

	protected $email;
    protected $last_name;

    /**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		$activationCode = $this->generateCode();
		$obj = new User();
		$obj->name = $data['name'];
        $obj->last_name = $data['last_name'];
		$obj->email = $data['email'];
		$obj->isActive = false;
		$obj->activationCode = $activationCode;
		$obj->password = bcrypt($data['password']);
		$obj->save();

		$this->username = $obj->name;
		$this->last_name = $obj->last_name;
		$this->id = $obj->id;
		$this->email = $data['email'];
		$this->activationCode = $activationCode;

		$this->sendActivationMail($data);

		return $obj;
	}

	/**
	 * @return string
     */
	protected function generateCode() {
		return Str::random();
	}

	/**
	 *
     */
	public function sendActivationMail($data) {
		$activationUrl = action(
			'UserController@getActivate',
			array(
				'userId' => $this->id,
				'activationCode'    => $this->activationCode,
			)
		);

		$template = 'emails/activation';
		if (!empty($data['registered_on_order'])) {
		    $template = 'emails/activationOnOrder';
        }

        Mail::send(
            $template,
            [
                'activationUrl' => $activationUrl,
                'username'      => $this->username,
                'last_name'     => $this->last_name
            ],
            function ($message) {
                $message->to($this->email)
                    ->subject('Thank you for registering!');
            }
        );
    }

}
