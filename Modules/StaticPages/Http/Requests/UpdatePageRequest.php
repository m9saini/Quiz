<?php

namespace Modules\StaticPages\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [
            'name' => 'required|unique:pages,name,' . $this->request->get('id'),
            'meta_keyword' => 'required|max:255',
            'meta_description' => 'required|max:255',
            'description' => 'required',
            'banner_image'=>'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
