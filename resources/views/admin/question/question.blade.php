  <div class="row">
       <div class="col-sm-12">
         <div class="form-group">
            <label class=" control-label">{{$lang['name']}} {{trans('Question')}}<span class="asterisk">*</span></label>
             <span class="err_name">
               {{ Form::textarea('question[]',$ques['question'], ['class'=>'form-control','rows'=>2,'id'=>$lang['code']."-question_".$qid,'placeholder'=>'Enter Question','title'=>'Please enter question.']) }}
             </span>
            
         </div>
       </div>
  </div>