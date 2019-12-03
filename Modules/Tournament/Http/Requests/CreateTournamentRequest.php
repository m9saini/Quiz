<?php

namespace Modules\Tournament\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
class CreateTournamentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'title' => 'required|max:250',
            'joinfees' => 'required|numeric',
            'size' => 'required|numeric|max:1024',
            'start_time'=>'required',
            'game_shedule'=>'required|in:d,w',
            'weekday'=>'required_if:game_shedule,==,w',
            'win_amount_type'=>'required|in:Per,Amt',
            'win_amount'=>'required|numeric',
        ];
        if(Request::Input('win_amount_type') == 'Per'){
            $rules['win_amount'] = 'required|numeric|min:1|max:100';
        }
        return $rules;
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