<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Tournament extends Model
{
     
    use Sluggable;

     protected $table = "tournament";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','title','joinfees','type','start_time','game_shedule','size','win_amount_type','win_amount','cat_id','is_repeated'];

   
     /**
     * Get the Tournament validation rules.
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

    public function rules()
    {
        return [
            'title' => 'required|max:250',
            'joinfees' => 'required|numeric',
            'size' => 'required|numeric',
            'start_time'=>'required',
            'game_shedule'=>'required|in:d,w',
            'type'=>'required',
            'win_amount_type'=>'required|in:Per,Amt',
            'win_amount'=>'required|numeric',
        ];
    }

 
    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return Tournament::where('slug',$slug)->first();//
    }

    /**
     * The function uses get list of tournament shedule type.
     * 
     * @param array $slug 
     */
    static public function winAmtTypeList($type=null)
    {
        $klist=['Per'=>'Percentage','Amt'=>'Amount'];
        return ($type)?(((isset($klist[$type]))?$klist[$type]:(($type=='haystack')?array_keys($klist):''))):$klist;
    }

    /**
     * The function uses get list of tournament shedule type.
     * 
     * @param array $slug 
     */
    static public function sheduleTypeList($type=null)
    {
        $klist=['d'=>'Daily','w'=>'Weekly'];
        return ($type)?(((isset($klist[$type]))?$klist[$type]:(($type=='haystack')?array_keys($klist):''))):$klist;
    }
}