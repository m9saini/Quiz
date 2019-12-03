<?php

namespace App;
use URL;
use Illuminate\Database\Eloquent\Model;


class QuestionDescription extends Model
{
	protected $table = "question_description";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lang_id','question_id','question'
    ];


}