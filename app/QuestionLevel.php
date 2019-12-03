<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class QuestionLevel extends Model
{
     
    //use Sluggable;

     protected $table = "question_levels";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

   



}