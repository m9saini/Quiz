 <div class="panel-body">
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans('menu.sidebar.email.form.name')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10">
          {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>'Name','title'=>'Please enter name.']) }}
      </div>
   </div>
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans('menu.sidebar.email.form.subject')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10">
         {{ Form::text('subject',null, ['required','class'=>'form-control','id'=>'subject','placeholder'=>'Subject','title'=>'Please enter subject.']) }}
      </div>
   </div>
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans('menu.sidebar.email.form.message')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10 ">
        <span class="err_editor"> {{ Form::textarea("body", null, ['required','class' => 'form-control', 'id'=>'ckeditor','rows'=>'10', 'title'=>"Please enter message.",'placeholder'=>"message"]) }}
        </span>
      </div>
   </div>
</div>
@section('uniquePageScript')
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
   jQuery(document).ready(function(){
      CKEDITOR.replace('ckeditor');
     // Basic Form
     jQuery("#validateForm").validate({
        ignore: [],
		debug: false,
		rules: {
			'body': {
				required: function() 
				{
					CKEDITOR.instances.ckeditor.updateElement();
				},
			},
		},
		highlight: function(element) {
			jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
    errorPlacement: function (error, element) { // render error placement for each input type
			if (element.attr("type") == "text") { 
				error.insertAfter(element);
			  // for chosen elements, need to insert the error after the 
			} else {
				 error.insertAfter('.err_editor');
			}
		},
		success: function(label,element) {
			jQuery(element).closest('.form-group').removeClass('has-error');
			label.remove();
		}
     }); 
   });
</script>
@endsection