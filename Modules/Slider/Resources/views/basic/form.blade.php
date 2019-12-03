 <div class="panel-body">
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.banner_image')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10">
       {{ Form::hidden('banner_image',null, ['id'=>'f_mediaId']) }}
      <input type="file" name="files" id="mediaId" accept="image/*" @if(isset($data)) value="{{$data->picture_path}}" @endif onchange="after_logo_select(this.id)"  style="display:none" class="form-control">
            <div class="input-group logo-duplicate_valid_msg">
                <input type="text" value="@if(isset($data->banner_image)) {{$data->banner_image}} @endif" readonly="" id="logo-duplicate" aria-describedby="basic-addon2" class="form-control" name="logo-duplicate">
                <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($data) && $data->picture_path))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
            </div>
			<div class="description"><small>Max 2Mb image size allowed. Allowed types : jpeg, png, jpg, gif.
      </div>
     
    </div>
    <!-- Html use for Gift Images logo -->
<div id="logo_popover_mediaId" style="display:none">
    <div id="logo_popover_content">
        @if(isset($data->banner_image))
        <img src="{{$data->picture_path}}" class="img-thumbnail" alt="" width="304" height="236" id="logo_popover_img_mediaId" >
        @else
        <p id="logo_popover_placeholder">No media has been selected yet</p>
        @endif
    </div>
</div>
</div> 
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.description')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10 ">
        <span class="err_editor"> 
        {{ Form::textarea("description", null, ['required','class' => 'form-control', 'id'=>'ckeditor','rows'=>'10', 'title'=>"Please enter desctiption.",'placeholder'=>"message"]) }}
        </span>
      </div>
   </div>
<div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.order')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10">
         {{ Form::text('slider_order',null, ['required','class'=>'form-control numberonly','id'=>'slider_order','placeholder'=>'Slider Order','title'=>'Please enter slider order.','maxlength'=>6]) }}
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
				'description': {
					required: function() 
					{
						CKEDITOR.instances.ckeditor.updateElement();
					},
				},
				'slider_order':{
					min: 1
				},
				'logo-duplicate': {
					required : true
				}			
			},
			messages : {
				'slider_order': {
					min: 'Invalid order number.'
				},
				'logo-duplicate': {
					required : "Slider image is required."
				}			
				
			},
			highlight: function(element) {
			 jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			errorPlacement: function (error, element) { // render error placement for each input type
				if (element.attr("name") == "logo-duplicate") { 
					error.insertAfter(".logo-duplicate_valid_msg");
				}else if (element.attr("type") == "number") { 
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
<script type="text/javascript">
//Function to insert filename in fake upload box
  function after_logo_select(id) {
        var uploadedFile = jQuery('#'+id)[0].files[0];
        jQuery('#logo-duplicate').val(uploadedFile.name);
        jQuery('#logo_popover_'+id+ ' #logo_popover_content').html('<img class="img-thumbnail" alt="" width="304" height="236" id="logo_popover_img_'+id+'" >');
        document.getElementById('logo_popover_img_'+id).src = URL.createObjectURL(uploadedFile);
        jQuery('#logo_popover_'+id).removeClass('disabled disabled_advanced');
    };

 jQuery(document).ready(function(){
      jQuery('#toggle_popover_mediaId').popover({
            html:true,
            title: 'Banner Image',
            container: 'body',
            placement: 'top',
            trigger: 'click',
            content: function(){
                return $('#logo_popover_mediaId').html();
            }
        }).click(function(){
            jQuery(this).children('i').toggleClass('fa-eye fa-eye-slash');
        }); 
  });

  /*-----------------------------------------------*/
 /* --------- Use For Image Upload --------------*/
jQuery(document).ready(function () { 
    $('input[type="file"]').change(function (event) {
      var MediaId = this.id;
      var inter;
        Lobibox.progress({
            title: 'Please wait',
            label: 'Uploading files...',
            progressTpl: '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () {
               
            },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $('.myprogress').text(percentComplete + '%');
                            $('.myprogress').css('width', percentComplete + '%');
                            var i = 0;
                        }
                    }, false);
                    return xhr;
            },
            closed: function () { 
                // 
            }
        });
         event.preventDefault();
         var data = new FormData();
         var files = $("#"+MediaId).get(0).files;
         data.append("_token", "{{ csrf_token() }}");
        if (files.length > 0) { data.append("files", files[0]); }
            else {
                $(function () {
                        (function () {
                            Lobibox.notify('info', {
                                rounded: false,
                                position: "top right",
                                delay: 5000,
                                delayIndicator: true,
                                msg: "Please select file to upload."
                            });
                        })();
                    });
                return false;
            }
            var extension = $("#"+MediaId).val().split('.').pop().toUpperCase();
            if (extension != "PNG" && extension != "JPG" && extension != "GIF" && extension != "JPEG") {
                 $(function () {
                        (function () {
                            Lobibox.notify('error', {
                                rounded: false,
                                position: "top right",
                                delay: 5000,
                                delayIndicator: true,
                                msg: "Invalid image file format."
                            });
                        })();
                    });
                  $("#"+MediaId).val('');
                return false;
            }
        $.ajax({
            type: "post",
            enctype: 'multipart/form-data',
            url: "{{route('slider.mediaStore')}}",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
             xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $('.myprogress').text(percentComplete + '%');
                                $('.myprogress').css('width', percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                 beforeSend: function (){
                        
                },
            success: function (data) {
                $(".btn-close").trigger("click");
                if(data['status']){
                     Lobibox.notify('success', {
                            position: "top right",
                            msg: 'File has been uploded successfully'
                        });
                     //console.log(MediaId);
                    $("#f_"+MediaId).val(data['filename']);
                }else{
                    Lobibox.notify('error', {
                            position: "top right",
                            msg: data['message']
                        });
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                 jQuery('#logo-duplicate_'+MediaId).val('');
                 var Arry = e.responseText;
                 var error = "";
                 JSON.parse(Arry, (k, v)=>{
                       if(typeof v != 'object'){
                       error +=v+"<br>"
                       }
                     })
                  Lobibox.notify('error', {
                         rounded: false,
                         position: "top right",
                         delay: 5000,
                         delayIndicator: true,
                         msg: error
                     });
            }
        });
    });
});
</script>
@endsection