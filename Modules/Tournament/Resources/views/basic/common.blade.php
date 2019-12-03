<div class="row">
  <div class="col-sm-12">
         <div class="form-group">
        <label class=" control-label">{{trans('Title')}} <span class="asterisk"> *</span></label>
        {{ Form::textarea('title',null, ['class'=>' form-control','rows'=>2,'id'=>"title",'placeholder'=>'Title','title'=>'Please enter title.']) }}
       </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
           <label class=" control-label">{{trans('Game Category')}} <span class="asterisk"></span></label>
            <span class="err_name">
             {!!Form::select('cat_id', $catList, NULL , ['id'=>'select_types','placeholder'=>'Select Type','title'=>'Please choose game shedule.', 'class' => 'form-control select2'])!!}
            </span>
            
        </div>
      </div> 

      <div class="col-sm-2">
         <div class="form-group">
        <label class=" control-label">{{trans('JoinFees')}} <span class="asterisk"> *</span></label>
        {{ Form::text('joinfees',null, ['class'=>'numberonly form-control','id'=>"joinfees",'placeholder'=>'joinfees','title'=>'Please enter joinfees.']) }}
       </div>
      </div>

        <div class="col-sm-2">
        <div class="form-group">
           <label class=" control-label">{{trans('Size of participate')}} <span class="asterisk">*</span></label>
            <span class="err_name">
              {{ Form::text('size',null, ['class'=>'form-control numberonly','id'=>'size','placeholder'=>'Size of participate','title'=>'Please enter size of tournament participate.']) }}
            </span>
            
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
           <label class=" control-label">{{trans('Win Amount Type')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {!!Form::select('win_amount_type',getWinningAmountType(),($data)?$data->win_amount_type:null , ['id'=>'win_amount_type','placeholder'=>'Amount Type','title'=>'Please choose game shedule.', 'class' => 'form-control select2'])!!}
            </span>
            
        </div>
      </div>  

      <div class="col-sm-2">
        <div class="form-group">
           <label class=" control-label">{{trans('Winning Amount')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {{ Form::text('win_amount',null, ['class'=>'form-control numberonly','id'=>'win_amount','placeholder'=>'Winning Amount','title'=>'Please enter winnning amount.']) }}
            </span>
           
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
           <label class=" control-label">{{trans('Game Shedule')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {!!Form::select('game_shedule', getTournamentShedule(), ($data)?$data->game_shedule:null, ['id'=>'game_shedule','placeholder'=>'Shedule Type','title'=>'Please choose game shedule.', 'class' => 'form-control select2'])!!}
            </span>
            
        </div>
      </div> 
      <div class="col-sm-2" id="weekdaydiv" style="display:{{$weekdayfield}};">
        <div class="form-group">
           <label class=" control-label">{{trans('Week Day')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {!!Form::select('weekday', getWeekDays(), ($data)?$data->weekday:null, ['id'=>'weekday','placeholder'=>'Select Day','title'=>'Please choose game weekday.', 'class' => 'form-control select2'])!!}
            </span>
            
        </div>
      </div>


      <div class="col-sm-1 bootstrap-timepicker">
        <div class="form-group">
           <label class=" control-label">{{trans('Start Time')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {{ Form::text('start_time',null, ['class'=>'form-control numberonly timepicker','id'=>'start_time','placeholder'=>'Start Time','title'=>'Please enter start time of tournament .']) }}
            </span>
           
        </div>
      </div>

      <div class="col-sm-1">
        <div class="form-group">
           <label class=" control-label">{{trans('Repeated')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {!!Form::select('is_repeated', ['0'=>'No','1'=>'Yes'], ($data && $data->is_repeated)?1:0 , ['id'=>'select_types','title'=>'Please choose game shedule.', 'class' => 'form-control select2'])!!}
            </span>
            
        </div>
      </div> 

     

 

    </div>