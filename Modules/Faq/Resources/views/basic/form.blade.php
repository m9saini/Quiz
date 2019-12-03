 <div class="panel-body">
   @php $list=getLanguageList();

   @endphp
    @foreach($list as $key=>$language)
  @php $hData=getFaqNameByLangId($data['id'],$language['code'])  @endphp 
   <div class="form-group">
      <label class="col-sm-2 control-label">{{$language['name']}} {{trans('faq::menu.sidebar.form.question')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10">
          {{ Form::textarea('question[]',$hData['question'], ['required','class'=>'form-control','id'=>'question','placeholder'=>'Question','rows'=>2,'title'=>'Please enter question.']) }}
      </div>
   </div>
 
   <div class="form-group">
      <label class="col-sm-2 control-label">{{$language['name']}} {{trans('faq::menu.sidebar.form.answer')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10 ">
        <span class="err_editor"> {{ Form::textarea("answer[]", $hData['answer'], ['required','class' => 'form-control', 'id'=>"ckeditor$key",'rows'=>10, 'title'=>"Please enter answer.",'placeholder'=>"Answer"]) }}
        </span>
      </div>
   </div>
   @endforeach

    <div class="form-group">
      <label class="col-sm-2 control-label">{{trans('faq::menu.sidebar.form.faq_order')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10">
         {{ Form::text('faq_order',null, ['required','class'=>'form-control numberonly','id'=>'faq_order','placeholder'=>'Faq order','title'=>'Please enter faq order number.','maxlength'=>6]) }}
      </div>
   </div>
</div>
@section('uniquePageScript')
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
   jQuery(document).ready(function(){
    CKEDITOR.replace('ckeditor0');
    CKEDITOR.replace('ckeditor1');
     // Basic Form
    jQuery("#validateForm").validate({
        ignore: [],
    		debug: false,
    		rules: {
    			'faq_order': {
    				min: 1
    			},
    			'answer': {
    				required: function() 
    				{
    					CKEDITOR.instances.ckeditor.updateElement();
    				},
    			},
    		},
    		messages : {
    			'faq_order': {
    				min: 'Invalid order number.'
    			},
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