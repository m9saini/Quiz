<?php namespace App\Http\Requests\Backend\Access\User;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

/**
 * Class CreateUserRequest
 * @package App\Http\Requests\Backend\Access\User
 */
class CreateUserRequest extends FormRequest {

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
			'name' => 'required',
			'email' => 'required|email|max:255|unique:users',
			'username'=>'required|unique:users|regex:/^\S*$/u',
			'role_id'=>'required',
            'password'=>'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
            'phone' => 'required|min:10|max:10|unique:users',
            'ph_country_id' => 'required',
           // 'g-recaptcha-response' => 'required|captcha',
		];
	}
}
