<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  use Sluggable,SluggableScopeHelpers;

    protected $table = "report";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','type','type_id','user_id','message','report_url','created_at'
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
                'source' => 'user_id',
                'method'=>function ($string, $separator) {
                        return md5(microtime());
                    },
                'onUpdate'=>false
            ]
        ];
    }

     /**
     * Return the User details by $user_id .
     *
     * @return array
     */
    public function user()
    {
         return $this->belongsTo('App\User', 'user_id');
    }

     /**
     * The function that should be find the slug to data for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }
}