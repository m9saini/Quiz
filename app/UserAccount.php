<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
     
    //use Sluggable;

     protected $table = "users_accounts";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','acc_number','acc_holder_name','acc_ifsc','bank_name','bank_branch'];

    /**
     * Get the User update profiles validation rules.
     *
     * @return array
     */
    public function accountRules($id=NULL)
    {
       $rules=[
            'user_id'=>'required|numeric',
            'acc_number'=>'required|numeric',
            'acc_holder_name' => 'required|string|max:150',
            'ifsc_code' => 'ifsc|required|string|max:25',
            'bank_name' => 'required|string|max:100',
            'bank_branch' => 'required|string|max:100',
        ];
        if($id)$rules=$rules+['id'=>'required|numeric'];
    return $rules;
    }

 
    /**
     * The function that should be find the userid for this model.
     * 
     * @param array $userid 
     */
    static public function findByUserId($userid)
    {
        return static::where('user_id',$userid)->first();
    }

    
}