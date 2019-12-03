@php 
$answer=$is_correct=$op_id=NULL;
if(!empty($opsnData) && isset($opsnData[$ansNum]['answer'])){
 $answer=$opsnData[$ansNum]['answer'];
 $is_correct=$opsnData[$ansNum]['is_correct'];
 $op_id=$opsnData[$ansNum]['id'];
 } 

@endphp

<div class="input-group control-group" id="{{$lcode}}-option_{{$ansNum}}">
	<div class="col-sm-6">
	  <label class=" control-label">{{$lang}} {{trans('Answer(Options)')}} <span class="asterisk">*</span></label>

	  {{ Form::textarea('answer[]',$answer, ['class'=>'form-control','rows'=>2,'id'=>"$lcode"."-answer_".$ansNum,'placeholder'=>'Enter Answer','title'=>'Please enter time durations in seconds.']) }}
</div>

<?php /*
<div class="col-sm-2">
 	<label class=" control-label">{{trans('Point')}} <span class="asterisk"> *</span></label>
 	{{ Form::number('answerponit[]',null, ['class'=>'form-control','id'=>"answerpoint_$loop_$ansNum",'placeholder'=>'Point','title'=>'Please enter point of answer.']) }}
 </div> */ ?>
@if($lang=='English')
 <div class="col-sm-2">
 	<label class=" control-label">{{trans('Image')}} <span class="asterisk"> </span></label>
 	{!! Form::file('ansimg[]', ['class' => 'image','id'=>"ansimg_".$ansNum]) !!}
 </div>

 <div class="col-sm-2">
	 <label class="control-label">{{trans('Answer')}} <span class="asterisk"> </span></label>
  {{ Form::radio('is_correct',$ansNum,$is_correct,['class'=>'getselected','data-item'=>"$ansNum",'id'=>"iscorrect_".$ansNum,'title'=>'if you checked,This option will be mark as correct answer.']) }}
 </div>
 @endif

 @if($ansNum>1)
  <div class="input-group-btn" id="{{$lcode}}-rm_{{$ansNum}}" >
    <button class="btn btn-primary remove_option" id="{{$lcode}}-rmbtn_{{$ansNum}}" data-item='{{$ansNum}}' type="button"><i class="glyphicon glyphicon-minus"></i></button>
  </div>
  @endif
 {!! Form::hidden('opstion_ids[]',$op_id,['id'=>"$lcode"."-opids_".$ansNum]) !!}
</div>