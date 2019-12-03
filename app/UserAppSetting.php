<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAppSetting extends Model
{
     protected $table = "user_app_settings";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['code','countryName','phonecode'];

   
    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findByUserId($userId=NULL)
    {
         $data=static::where('user_id',$userId)->first();
        if(empty($data)){
            $data=UserAppSetting::appSettingKeys();
        }
        return $data;
    }


    static public function appSettingKeys(){
        return['notification'=>'on','background_sound'=>'on','touch_sound'=>'on','haptic_feedback'=>'on','default_language'=>'en','theme_color'=>'dark'];
    }
}
