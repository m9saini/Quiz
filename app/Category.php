<?php

namespace App;
use URL;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\CategoryDescription;

class Category extends Model
{
	use Sluggable,SoftDeletes,SluggableScopeHelpers;

	protected $table = "category";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id','description','title','category_order','category_image','status','created_at'
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
                'source' => 'title',
                'onUpdate'=>false
            ]
        ];
    }


    /**
     * The function that should be Belongs to category description.
     */
    public function categoryDescription()
    {
        return $this->hasMany('App\CategoryDescription');
    }
    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return Category::where('slug',$slug)->first();//
    }

	//Getting list 
	static public function getCategoryList()
    {
        return Category::where('deleted_at',NULL)->where('status',1)->pluck('title','id')->toArray();
    }


    //Getting list 
    static public function getCategoryDescList($cat)
    {
        return CategoryDescription::where('deleted_at',NULL)->where('category_id',$cat)->pluck('name','id')->toArray();
    }

    //Getting picture path
    public function getPicturePathAttribute()
    {
        return ($this->category_image) ? URL::to('storage/app/public/category/'.$this->category_image) : NULL;
    }

    public function afterSave($fillable){

        $catDesc=CategoryDescription::where('lang_id',$fillable['lang_id'])->where('category_id',$fillable['category_id'])->first();
        if(empty($catDesc))
            $catDesc= new CategoryDescription();
        $catDesc->fill($fillable);
        return ($catDesc->save())?$catDesc->id:false;
    }
}