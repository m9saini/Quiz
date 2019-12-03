<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AlertController;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Question; // question model here
use App\Category;
use App\QuestionOption;
use App\QuestionDescription;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Mail,Redirect,Response,Session,URL,View,Validator;
use Carbon\Carbon;

class QuestionController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
    protected $question,  $request, $alert;
    public function __construct(Question $question, Request $request,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' =>'saveMedia','BackReset','BackForgotPassword','getAjaxData']);
        $this->model=$question;
        $this->alert=$alertController;
        $this->request=$request;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // fetch all not deleted records
      $questions = Question::where('is_deleted',0)->paginate(10);
      return view('admin.question.index', compact('questions'));
    }

    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
        try {
        DB::statement(DB::raw('set @rownum=0'));
        $lists = $this->model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('is_deleted',0);
        return DataTables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton( [ //'delete'=>['questions.destroy', [$list->id]],
                       'deleteAjax'=>['questions.destroy', [$list->id]],
                        'edit'=>['questions.edit', [$list->id]],
                        getStatusAI($list->status)=>['questions.status',[$list->id]],
                                //'view'=>['questions.show',[$list->id]]
                              ]);
                    $status =keyExist($dispalyButton, getStatusAI($list->status));
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                    $view = keyExist($dispalyButton, 'view');
                      return $view.$status.$edit.$delete;
                })
                ->editColumn('category_id', function($list){
                    //return '';
                    return ($cat=getCategoryNameByLangId($list->category_id,DEFAULT_LANGUAGE_CODE))?$cat->name:NULL;
                })
                ->make(true);
        }
        catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $catList=Category::getCategoryList();
        $quesLevels=Question::getQuestionLevels();
        $quesTypes=Question::getQuestionTypes();
        $ansNum=2;
        return view('admin.question.create',compact('catList','quesLevels','quesTypes','ansNum'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      if(!empty($request)){
  			$message	=	array(
  						'question.*.required' 		=>	"Please enter question.",
              'category_id.required'  =>  "Please select category.",
              'level_id.required'     =>  "Please select level.",
              'durations.required'    =>  "Please enter durations.",
              'point.required'        =>  "Please enter point.",
              'answer.*.required'     =>  "Please enter answer.",
              'is_correct.required'   =>  "Please select correct answer",
              'selected_ans'          =>  "Please select correct answer",
  					);
  			$validate	=	array(
  					'question'			=>	'required|array|min:2',
            'question.*'    =>  'required|string|max:255',
            'category_id'   =>  'required|numeric',
            'level_id'      =>  'required|numeric',
            'durations'     =>  'required|numeric',
            'point'         =>  'required|numeric',
            'is_correct'    =>  'required',
            'selected_ans'  =>  'required',
            'answer'        =>  'required|array|min:2',
            'answer.*'      =>  'required|string|max:255',
  				);
        if ($this->validate($request,$validate,$message)) {

          //dd($request->all());
          $lang=getLanguageList();
          $question 						   =	new Question;
  				$question->question		   =	strip_tags($request->question[0]);
          $question->lang_id        =  DEFAULT_LANGUAGE_CODE;
          $question->category_id    =  $request->category_id;
          $question->level_id       =  $request->level_id;
          $question->point          =  $request->point;
          $question->durations      =  $request->durations;
  				$question->status			   =	1;
  				$question->is_deleted		 =	0;   
          if($request->hasFile('image')){
          $filename=upload($request->file('image'),storage_path() . '/app/public/question/');
          $question->image    =  ($filename)?$filename:NULL; 
          }
          if($question->save()){
            $qid=$question->id;
            $answer=$request->answer;
            $ansloop=(count($request->answer)/2);
            $ansKey=0;
            foreach ($lang as $key => $value) {
              
              $qDesc['lang_id']     = $value['code'];
              $qDesc['question_id'] = $qid;
              $qDesc['question']    = strip_tags($request->question[$key]);
              $question->questionDescSave($qDesc);

              for($i=0;$i<$ansloop;$i++) {
                $answers['question_id']   = $qid;
                $answers['lang_id']       = $value['code'];
                $answers['answer']        = $answer[$ansKey];
                $answers['is_correct']    = ($i==$request->selected_ans)?1:0;
                $answers['status']        = 1;
                $answers['is_deleted']    = 0;
                if($request->hasFile($request->file('ansimg.'.$ansKey))) {
                  $oFilename=upload($request->file('ansimg.'.$ansKey),storage_path() . '/app/public/question/answer/');
                    $answers['image']    =  ($oFilename)?$oFilename:NULL; 
                }

                $question->questionOptionsSave($answers);
                $ansKey++;
              }
            }

            Session::flash('success', 'Question has been added successfully.');
    				return Redirect::route("questions.index");
          }else {
            Session::flash('error', 'Something went wrong.');
          }
        }

  		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $model = Question::findOrFail($id);
      return view('admin.question.edit', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $model = Question::findOrFail($id);
      $catList=Category::getCategoryList();
      $quesLevels=Question::getQuestionLevels();
      $quesTypes=Question::getQuestionTypes();
      $ansNum=(countOptions($id)/getDefaultLanguageCount());
      return view('admin.question.edit', compact('model','catList','quesLevels','quesTypes','ansNum'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          /*  print_r($request->answer);
      $answer=$request->answer;
      echo $answer[0];
      echo $ansloop=(count($request->answer)/2);
      dd($request->all()); die;*/
      if(!empty($request)){
          $message  = array(
              'question.*.required'     =>  "Please enter question.",
              'category_id.required'  =>  "Please select category.",
              'level_id.required'     =>  "Please select level.",
              'durations.required'    =>  "Please enter durations.",
              'point.required'        =>  "Please enter point.",
              'answer.*.required'     =>  "Please enter answer.",
              'is_correct.required'   =>  "Please select correct answer",
              'selected_ans'          =>  "Please select correct answer",
            );
        $validate = array(
            'question'      =>  'required|array|min:2',
            'question.*'    =>  'required|string|max:255',
            'category_id'   =>  'required|numeric',
            'level_id'      =>  'required|numeric',
            'durations'     =>  'required|numeric',
            'point'         =>  'required|numeric',
            'is_correct'    =>  'required',
            'selected_ans'  =>  'required',
            'answer'        =>  'required|array|min:2',
            'answer.*'      =>  'required|string|max:255',
          );
        if ($this->validate($request,$validate,$message)) {
          $question 						=	Question::findOrFail($id);
          $lang=getLanguageList();
          $question->question      =  strip_tags($request->question[0]);
          $question->lang_id        =  DEFAULT_LANGUAGE_CODE;
          $question->category_id    =  $request->category_id;
          $question->level_id       =  $request->level_id;
          $question->point          =  $request->point;
          $question->durations      =  $request->durations;
         // $question->status        =  1;
          $question->is_deleted    =  0;   
          if($request->hasFile('image')){
          $filename=upload($request->file('image'),storage_path() . '/app/public/question/');
          $question->image    =  ($filename)?$filename:NULL; 
          }
          if($question->save()){

            $answer=$request->answer;
            $ansOptions=$request->opstion_ids;
            $ansloop=(count($request->answer)/getDefaultLanguageCount());
            $ansKey=0;
            foreach ($lang as $key => $value) { 
              $qDesc['lang_id']     = $value['code'];
              $qDesc['question_id'] = $question->id;
              $qDesc['question']    = strip_tags($request->question[$key]);
              $question->questionDescSave($qDesc);

              for($i=0;$i<$ansloop;$i++) {
                $answers['question_id']   = $question->id;
                $answers['lang_id']       = $value['code'];
                $answers['answer']        = $answer[$ansKey];
                $answers['is_correct']    = ($i==$request->selected_ans)?1:0;
                $answers['status']        = 1;
                $answers['is_deleted']    = 0;
                if($request->hasFile($request->file('ansimg.'.$ansKey))) {
                  $oFilename=upload($request->file('ansimg.'.$ansKey),storage_path() . '/app/public/question/answer/');
                    $answers['image']    =  ($oFilename)?$oFilename:NULL; 
                }
                $question->questionOptionsSave($answers,$ansOptions[$i]);
                $ansKey++;
              }
            }
            Session::flash('success', 'Question has been updated successfully.');
            return Redirect::route("questions.index");
          }else {
            Session::flash('error', 'Something went wrong.');
          }
        }

      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    protected function destroy(Request $request,$id)
   {
       try
       {
          $model = $this->model->whereId($id)->first();
           //$model->delete();
           $model->is_deleted = 1;
           $model->deleted_at = Carbon::now();
           $model->save();
           $type = 'success'; $message = 'Question has been deleted successfully.';
           if($request->ajax()){
               return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
           }
           $request->session()->flash($type, $message);
               return back();
       }
       catch (\Exception $e)
       {
           $type = 'warning'; $message = 'Either something went wrong or invalid access.';
           if($request->ajax()){
               return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
           }
           $request->session()->flash($type, $message);
               return back();
       }
   }

    public function change_status(Request $request,$id)
    {
        if($id){
            $change = $this->model->find($id);
            $active = $change->status;
            if ($id != null)
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                     $this->model->where('id', $id)->update($update_arr);
                }
                else
                {
                    $update_arr = array('status' => 1);
                    $this->model->where('id', $id)
                        ->update($update_arr);
                }
                 $message = 'Question status changed successfully.';
                 $type = 'success';
            }else{
                 $message = 'You have something went wrong.';
                 $type = 'warning';
            }
             //return back();
        }else{
             $message = 'You have something went wrong.';
             $type = 'warning';
        }
        if($request->ajax()){
            return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
        }
         return back();
         Session::flash($type, $message);
    }


    public function getAjaxOption(Request $request){

      $ansNum=$request->get('ansNum');
      $lang=$request->get('lang');
      $loop=$request->get('loop');
      $lcode=$request->get('lcode');
      $data=view('admin.question.options', compact('ansNum','lang','loop','lcode'));
     
      return $data;
    }

    public function importQuestions(Request $request){

      $rules = array(
        'file' => 'required|mimes:csv,xls,xlsx',
    );

    $validator = Validator::make(Input::all(), $rules);
    if ($this->validate($request,$rules)) 
    {
        try {

         Excel::import(new QuestionsImport,request()->file('file'));
            \Session::flash('success', 'Questions uploaded successfully.');
            return redirect(route('questions.index'));
        } catch (\Exception $e) {
            \Session::flash('error', $e->getMessage());
            return redirect(route('questions.index'));
        }
    } 

    }
}
