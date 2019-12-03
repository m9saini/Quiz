<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
   protected $table = "news";
    protected $fillable = [
        'slug','title','short_description','description','news_order','image','created_at'
    ];

    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

    public function getPicturePathAttribute()
    {
        return ($this->image) ? URL::to('storage/news/'.$this->image) : NULL;
    }	
}
