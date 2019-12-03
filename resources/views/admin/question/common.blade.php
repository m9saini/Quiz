<div class="row">
      <div class="col-sm-3">
        <div class="form-group">
           <label class=" control-label">{{trans('Category')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {!!Form::select('category_id', $catList, $model['category_id'] , ['id'=>'select_category','placeholder'=>'Please select Category','title'=>'Please choose Category.', 'class' => 'form-control select2'])!!}
            </span>
            
        </div>
      </div>

      <div class="col-sm-2">
        <label class=" control-label">{{trans('Point')}} <span class="asterisk"> *</span></label>
        {{ Form::text('point',null, ['class'=>'numberonly form-control','id'=>"question_point",'placeholder'=>'Point','title'=>'Please enter point of answer.']) }}
       </div>

    <?php /*  <div class="col-sm-2">
        <div class="form-group">
           <label class=" control-label">{{trans('Types')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {!!Form::select('type_id', $quesTypes, 0 , ['id'=>'select_types','placeholder'=>'Select Type','title'=>'Please choose question type.', 'class' => 'form-control select2'])!!}
            </span>
            
        </div>
      </div> */ ?>

      <div class="col-sm-2">
        <div class="form-group">
           <label class=" control-label">{{trans('Levels')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {!!Form::select('level_id', $quesLevels, $model['level_id'] , ['id'=>'select_level','placeholder'=>'Select Level','title'=>'Please choose level.', 'class' => 'form-control select2'])!!}
            </span>
            
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
           <label class=" control-label">{{trans('Question Duration(Seconds Only)')}} <span class="asterisk">*</span></label>
            <span class="err_name">
             {{ Form::text('durations',null, ['class'=>'form-control numberonly','id'=>'durations','placeholder'=>'Question Durations','title'=>'Please enter time durations in seconds.']) }}
            </span>
           
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
           <label class=" control-label">{{trans('Image')}} <span class="asterisk"></span></label>
            <span class="err_name">
             {!! Form::file('image', ['class' => 'image','id'=>"option_img"]) !!}
            </span>
            
        </div>
      </div>

    </div>