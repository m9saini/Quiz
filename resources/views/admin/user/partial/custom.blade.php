<!-- {!! Html::style('assets/admin/css/jquery.tagsinput.css') !!}
<script src="{{URL::to('assets/admin/js/jquery.tagsinput.min.js')}}"></script> -->
<script type="text/javascript">
    $(document).ready(function(){
        $(".select2").select2();
    //group add limit
    var maxGroup = 5; 
	var z = $('body').find('.qualification_group').length;
    //add more fields group
    $(".addMore").click(function(){
        if($('body').find('.qualification_group').length < maxGroup){
            var fieldHTML = '<div class="qualification_group quality_selector" id="">'+$(".qualification_groupCopy").html()+'</div>';
            $('body').find('.qualification_group:last').after(fieldHTML);
            jQuery(".quality_selector"+" select").chosen({'width':'100%','white-space':'nowrap'});
        }else{
            alert('Maximum '+maxGroup+' qualification groups are allowed.');
        }
    });
    
    //remove fields group
    $("body").on("click",".remove",function(){
        $(this).parents(".qualification_group").remove();
    });
});
   /*-----------------------------------------------*/
  /*------------ Form Validiate--------------------*/
     jQuery(document).ready(function(){
     $('[id^=contact_number]').keypress(validateNumber);
     // Chosen Select
     jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
       // Tags Input
     //jQuery('#skills').tagsInput({width:'auto'});
     // Basic Form
     jQuery("#validateForm").validate({
        ignore: [],
            debug: false,
            rules: {
                password: {
                  minlength: 6,
                }, 
                password_confirmation: {
                  equalTo: "#password",
                }, 
                contact_number: {
                  number: true,
                  minlength: 10,
                  maxlength: 10
                },
                description: {
                   minlength: 150
                },
            },
              messages : {
                password: {
                  minlength: 'Password must be minimum 6 digits.'
                }, 
              password_confirmation: {
                 equalTo: 'Your password not match.'
              }, 
              description: {
                 minlength: 'Description must be minimum 150 charectors.'
              },
            },
  
       highlight: function(element) {
         jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
       },
        errorPlacement: function (error, element) { 
          if(element.attr("id") == 'country_id' || element.attr("id") == 'qualification_id' || element.attr("id") == 'degree' || element.attr("id") == 'college_name' || element.attr("id") == 'graduation_year' ){ 
            error.insertAfter('.err_'+element.attr("id"));
          }else{
              error.insertAfter('.err_'+element.attr("name"));
          }               
        }, 
       success: function(label,element) {
         jQuery(element).closest('.form-group').removeClass('has-error');
          label.remove();
       },
	 
     }); 
   });

function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if ( key < 48 || key > 57 ) {
        return false;
    } else {
        return true;
    }
};
  /*-----------------------------------------------*/
 /* --------- insert filename in fake upload box--*/
//Function to insert filename in fake upload box
  function after_logo_select(id) {
        var uploadedFile = jQuery('#'+id)[0].files[0];
        jQuery('#logo-duplicate_'+id).val(uploadedFile.name);
        jQuery('#logo_popover_'+id+ ' #logo_popover_content').html('<img class="img-thumbnail" alt="" width="304" height="236" id="logo_popover_img_'+id+'" >');
        document.getElementById('logo_popover_img_'+id).src = URL.createObjectURL(uploadedFile);
        jQuery('#logo_popover_'+id).removeClass('disabled disabled_advanced');
    };

 jQuery(document).ready(function(){
      jQuery('#toggle_popover_mediaId').popover({
            html:true,
            title: 'Profile Picture',
            container: 'body',
            placement: 'top',
            trigger: 'click',
            content: function(){
                return $('#logo_popover_mediaId').html();
            }
        }).click(function(){
            jQuery(this).children('i').toggleClass('fa-eye fa-eye-slash');
        });   

      jQuery('#toggle_popover_headermediaId').popover({
            html:true,
            title: 'Cover Image',
            container: 'body',
            placement: 'top',
            trigger: 'click',
            content: function(){
                return $('#logo_popover_headermediaId').html();
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
            url: "{{route('media.store')}}",
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

//On role change Hide show form fields and add remove required attributes
// $(document).ready(function(){
//     $("#select_role").change(function(){ 
//         $(this).find("option:selected").each(function(){
//             var optionValue = $(this).attr("value");
//             if(optionValue){
//                 $(".box").not(".form_" + optionValue).hide();
//                 $(".form_" + optionValue).show();
//                 if(optionValue == 'seller'){
//                     $('.req_seller').prop('required',true);
//                 }else{
//                   $('.req_seller').prop('required',false);
//                 }
//             } else{
//                 $(".box").hide();
//             }
//         });
//     }).change();
// });
</script>
<style type="text/css">.row{margin-bottom:10px;}</style>
