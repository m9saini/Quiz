<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class QuestionOption extends Model
{
     
    //use Sluggable;

     protected $table = "question_options";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lang_id','question_id','is_correct','answer','status','is_deleted'];

}