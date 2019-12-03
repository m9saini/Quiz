<?php namespace App\Http\Requests\Backend\Access\User;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

/**
 * Class MediaRequest
 * @package App\Http\Requests\Backend\Access\User
 */
class MediaRequest extends FormRequest {

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
			'files' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		];
	}
}
