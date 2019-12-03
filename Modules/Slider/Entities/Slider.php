<?php

namespace Modules\Slider\Entities;

use URL;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
	use Sluggable,SoftDeletes,SluggableScopeHelpers;

	protected $table = "slider";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','description','slider_order','banner_image','status','created_at'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slug',
                'onUpdate'=>false
            ]
        ];
    }
    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

	//Getting picture path
	public function getPicturePathAttribute()
    {
        return ($this->banner_image) ? URL::to('storage/slider/'.$this->banner_image) : NULL;
    }	
}