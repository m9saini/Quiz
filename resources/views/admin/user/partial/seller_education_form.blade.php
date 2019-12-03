<div class="row">
  <div class="col-sm-6">
  <div class="form-group">
      <label class="control-label">{{trans('menu.sidebar.users.form.country_clg_university')}} <span class="asterisk">*</span></label>
     <span class="err_country_id">    {!!Form::select('country_id[]', getCountryList(), (isset($education)) ? $education->country_id : null , ['id'=>'country_id','placeholder'=>trans('menu.sidebar.users.form.country_clg_university'),'title'=>'Please select country of college/ university.', 'class' => 'form-control chosen-select req_seller' ])!!}</span>
  </div>
</div><!-- col-sm-6 --> 
<div class="col-sm-6">
  <div class="form-group">
      <label class="control-label">{{trans('menu.sidebar.users.form.education_title')}} <span class="asterisk">*</span></label>
     <span class="err_qualification_id">    {!!Form::select('qualification_id[]', getQualificationsList(), (isset($education)) ? $education->qualification_id : null , ['id'=>'qualification_id','placeholder'=>trans('menu.sidebar.users.form.education_title'),'title'=>'Please choose qualification title.', 'class' => 'form-control chosen-select req_seller' ])!!}</span>
  </div>
</div><!-- col-sm-6 -->
</div>
<div class="row">
<div class="col-sm-6">
  <div class="form-group">
    <label class="control-label">{{trans('menu.sidebar.users.form.degree')}} <span class="asterisk">*</span></label>
   <span class="err_degree"> {{ Form::text('degree[]',(isset($education)) ? $education->degree : null, ['class'=>'form-control req_seller','id'=>'degree','placeholder'=>'Degree','title'=>'Please enter degree.']) }}</span>
  </div>
</div><!-- col-sm-6 -->
 <div class="col-sm-6">
  <div class="form-group">
    <label class="control-label">{{trans('menu.sidebar.users.form.college_name')}} <span class="asterisk">*</span></label>
   <span class="err_college_name"> {{ Form::text('college_name[]',(isset($education)) ? $education->college_name : null, ['class'=>'form-control req_seller','id'=>'college_name','placeholder'=>'College name','title'=>'Please enter college name.']) }}</span>
  </div>
</div><!-- col-sm-6 -->
</div>
<div class="row">
<div class="col-sm-6">
  <div class="form-group">
      <label class="control-label">{{trans('menu.sidebar.users.form.year_of_education')}} <span class="asterisk">*</span></label>
     <span class="err_graduation_year"> {{ Form::selectYear('graduation_year[]', 1980, date('Y'), (isset($education)) ? $education->graduation_year : '2014', ['id'=>'graduation_year','placeholder'=>trans('menu.sidebar.users.form.year_of_education'),'title'=>'Please choose qualification year.', 'class' => 'form-control chosen-select',  'required'=>'required' ]) }}  </span>
  </div>
</div><!-- col-sm-6 -->
</div>