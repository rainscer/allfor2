<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class OrderRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [

			'd_user_address'	=> 'required', //["required",'regex:/^[a-zA-Z0-9 ,.\-:"()]*?$/'],
			'd_user_city' 		=> 'required',
			'd_user_index' 		=> 'required|numeric',
			'name' 				=> 'required',
            'last_name' 		=> 'required',
			'd_user_phone' 		=> 'required',
			'email'				=> 'required|email'

		];
	}

}
