<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use URL;

class UsersKyc extends Model
{
     
    //use Sluggable;

     protected $table = "users_kyc";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','kyc_type','doc_name','doc_number','doc_address','doc_image'];

   
     /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
   /* public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'kyc_type'
            ],
            
        ];
    }*/


     /**
     * Get the User update profiles validation rules.
     *
     * @return array
     */
    public function kycRules()
    {
       return [
            'user_id'=>'required',
            'kyc_type' => 'required|in:pc,ac',
            'doc_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'doc_number' => 'required|string|max:25',
        ];
    }

 

    /**
     * The function uses get list of KYC type.
     * 
     * @param array $slug 
     */
    public function kycTypeList($type=null)
    {
        $klist=['pc'=>'Pan Card','ac'=>'Aadhar Card'];
        return ($type)?(((isset($klist[$type]))?$klist[$type]:(($type=='haystack')?array_keys($klist):''))):$klist;
    }

    public function getPicturePathAttribute()
    {
        return ($this->doc_image) ? URL::to('storage/app/public/kyc/'.$this->doc_image) : URL::to('storage/kyc/kycdemo.jpeg');
    }

    public function getKycFullnameAttribute()
    {
        $fullname='';
        if($this->kyc_type) $fullname=$this->kycTypeList($this->kyc_type);
        return $fullname;
    }
}