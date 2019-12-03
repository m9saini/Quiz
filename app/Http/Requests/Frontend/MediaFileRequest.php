<?php namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

/**
 * Class MediaFileRequest
 * @package App\Http\Requests\Frontend
 */
class MediaFileRequest extends FormRequest {

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
			'files' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,doc,mp4,mov,ogg|max:20480',
		];
	}
}
