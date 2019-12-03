<?php

namespace App;
use URL;
use Illuminate\Database\Eloquent\Model;


class FaqDescription extends Model
{
	protected $table = "faq_description";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lang_id','faq_id','question','answer'
    ];

     /**
     * The function that should be Belongs to category description.
     */
   /* public function category()
    {
        return $this->belongsTo('App\Category');
    }*/
}