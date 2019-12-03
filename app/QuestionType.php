<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class QuestionType extends Model
{
     
    //use Sluggable;

     protected $table = "question_types";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}