 <div class="panel-body">
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.config_title')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10">
          {{ Form::text('config_title',null, ['required','class'=>'form-control','id'=>'config_title','placeholder'=>'Title','title'=>'Please enter config title.']) }}
      </div>
   </div>
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.config_value')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10">
         {{ Form::text('config_value',null, ['required','class'=>'form-control','id'=>'config_value','placeholder'=>'Value','title'=>'Please enter config value.']) }}
      </div>
   </div>
</div>
@section('uniquePageScript')
<script>
   jQuery(document).ready(function(){
     jQuery("#validateForm").validate({
        ignore: [],
    		debug: false,
    		rules: {
    		},
    		highlight: function(element) {
    			jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    		},
        errorPlacement: function (error, element) { // render error placement for each input type
               if (element.attr("type") == "text" || element.attr("type") == "number") { 
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