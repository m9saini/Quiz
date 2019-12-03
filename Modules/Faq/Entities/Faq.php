<?php

namespace Modules\Faq\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\FaqDescription;

class Faq extends Model
{
    use Sluggable,SoftDeletes,SluggableScopeHelpers;

	protected $table = "faqs";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','question','answer','faq_order','created_at'
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
                'source' => 'question',
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

    public function afterSave($fillable){

        $fDesc=FaqDescription::where('faq_id',$fillable['faq_id'])->where('lang_id',$fillable['lang_id'])->first();
        if(empty($fDesc))
        $fDesc= new FaqDescription();
        $fDesc->question=$fillable['question'];
        $fDesc->answer=$fillable['answer'];
        $fDesc->faq_id=$fillable['faq_id'];
        $fDesc->lang_id=$fillable['lang_id'];
        return ($fDesc->save())?$fDesc->id:NULL;

    }
}