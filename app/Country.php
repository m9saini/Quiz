<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
     protected $table = "countries";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code','countryName','phonecode'];

   
    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findByCode($code)
    {
        return static::where('code',$code)->first();
    }
}
