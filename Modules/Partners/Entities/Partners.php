<?php

namespace Modules\Partners\Entities;

use Illuminate\Database\Eloquent\Model;
use URL;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partners extends Model
{
    protected $fillable = ['name','slug','description','image','status'];
    protected $table = "partners";

    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

    public function getPicturePathAttribute()
    {
      return ($this->image) ? URL::to('storage/partners/'.$this->image) : URL::to('storage/partners/noimage.jpg');
    }
    	
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate'=>true
            ]
        ];
    }
}
