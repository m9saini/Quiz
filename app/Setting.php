<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
     protected $table = "settings";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key_value'];

   
    public function rules()
    {
        return [
           // 'language' => 'required',
            'commission' => 'required|numeric',
        ];
    }

    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findByKeyName($kname)
    {
        return static::where('key_name',$kname)->first();
    }
}
