<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\QuestionLevel;
use App\QuestionType;
use App\QuestionOption;
use App\QuestionDescription;

class Question extends Model
{

    protected $table = "questions";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lang_id','category_id','level_id','question','durations','point'];


        //Get Question Levels
    static public function getQuestionLevels(){

        return QuestionLevel::pluck('title','id')->toArray();
    }

        //Get Question Levels
    static public function getQuestionTypes(){

        return QuestionType::where('status',1)->pluck('title','id')->toArray();
    }

    public function questionDescSave($qd){

    	$qDesc=QuestionDescription::where('lang_id',$qd['lang_id'])
    							  ->where('question_id',$qd['question_id'])
    							  ->first();
    	if(empty($qDesc))
    		$qDesc= new QuestionDescription();
    	$qDesc->fill($qd);
    	$qDesc->save();
    }
    	// Answer Save
    public function questionOptionsSave($ans,$id=NULL){
    	if($id){
            $answer=QuestionOption::where('id',$id)
        							->where('lang_id',$ans['lang_id'])
        							->where('question_id',$ans['question_id'])
        							->first();
            if(!empty($answer)){
            	$answer->fill($ans);
            	$answer->save();
            }
        }else{
            $answer= new QuestionOption();
            $answer->fill($ans);
            $answer->save();
        }
    }

    
}