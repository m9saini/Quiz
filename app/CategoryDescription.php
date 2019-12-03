<?php

namespace App;
use URL;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class CategoryDescription extends Model
{
	protected $table = "category_description";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lang_id','category_id','name'
    ];

     /**
     * The function that should be Belongs to category description.
     */
   /* public function category()
    {
        return $this->belongsTo('App\Category');
    }*/
}