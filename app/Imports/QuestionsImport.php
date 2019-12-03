<?php
namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
class QuestionsImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
     /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $lang=getLanguageList();
        $answer=$row[14];
        $question= new Question(
                    ['lang_id'=>1,
                    'category_id'=>     $row[0],
                    'level_id'=>        $row[1],
                    'point'=>           $row[2],
                    'durations'=>       $row[3],
                    'question'=>        trim(strip_tags($row[4])),
                    'status'  =>  0,
                    'is_deleted' => 0
                    ]);
        if($question->save()){

            $ansloop=4;
            $ansKey=0;
            $options=[5,9];
            foreach ($lang as $key => $value) { 
              $qDesc['lang_id']     = $value['id'];
              $qDesc['question_id'] = $question->id;
              $qDesc['question']    = trim(strip_tags($row[4+$key]));
              $question->questionDescSave($qDesc);

              for($i=1;$i<=$ansloop;$i++) {
                $answers['question_id']   = $question->id;
                $answers['lang_id']       = $value['id'];
                $answers['answer']        = $row[$options[$key]+$i];
                $answers['is_correct']    = ($i==$answer)?1:0;
                $answers['status']        = 1;
                $answers['is_deleted']    = 0;
                $question->questionOptionsSave($answers);
                $ansKey++;
              }
            }
            
            return $question;
          }
    }


}