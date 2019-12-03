 @include('admin.question.common',compact('model','catList','quesLevels','quesTypes','ansNum'))
 
 @php $list=getLanguageList();
      $lang_list=json_encode($list);
      $countAnsNum=0;
     $is_correct=NULL;
   @endphp
 
  @foreach($list as $key=>$language)
  @php $qData=getQuestionByLangId($model['id'],$language['code']);  
  $opsnData=getOptionsOfQuestionByLangId($model['id'],$language['code']) ;
  @endphp 

  @include('admin.question.question',['ques'=>$qData,'lang'=>$language,'qid'=>$key])
   
    <div id="main_block_option">
      
    <div class="row" id="optionblock_{{$key}}">

       @for ($i = 0; $i<$ansNum; $i++) 
       
      @include('admin.question.options',['opsnData'=>$opsnData,'ansNum'=>$i,'lcode'=>$language['code'],'lang'=>$language['name'],'loop'=>$key])

       @php 

       if(!empty($opsnData) && isset($opsnData[$i]['answer'])){
            if($opsnData[$i]['is_correct']==1)$is_correct=$i;
 } 
        $countAnsNum++  @endphp
      @endfor
      

    </div>
    @if($key==0)
    <div class="input-group-btn" id="add_btn" >
      <button class="btn btn-success add-more" data-item='2' data-lang="{{$language['name']}}" data-block="{{$key}}" type="button"><i class="glyphicon glyphicon-plus"></i>  </button>
    </div>
    @endif
  </div>

  @endforeach
   {!! Form::hidden('selected_ans',$is_correct,['id'=>'selected_ans']) !!}



@php $list=getLanguageList();
      $lang_list=json_encode($list)
   @endphp

@section('uniquePageScript')

<script type="text/javascript">
 var countOfOption=2;
 var totaloptions=2;
 var xi=0;


  var lang_list={!!$lang_list!!};
     
  //var x = 0; //initialize counter for text box
  $(document).on('click','.add-more',function(e){
  if(countOfOption<6) {
    countOfOption=parseInt(countOfOption);
     e.preventDefault();
     var kk=0;
     loopLenght=lang_list.length
     var ajaxSuccess=0;    
        lpn(kk);       
    
     function lpn(hh){
      if(kk<loopLenght)
     {
          var lang=lang_list[hh]['name'];
          var lcode=lang_list[hh]['code'];
          $.ajax({method:'POST',
                  url: '{!! route('questions.ajaxoption') !!}',
                  data:{ansNum:countOfOption,lang:lang,lcode:lcode,loop:totaloptions} ,
                  success: function(result){
                        addNewOption(hh,result);
                        //countTotalOption();
                        //result='';
          
                    }
            });
        kk++;
        lpn(kk);   
     } 

    } //close lang block

    optionCountIncress();
    xi=0;
  }
  });

  function addNewOption(iii,result){ console.log(iii);
    $("#optionblock_"+iii).append(result);
  }

  function optionCountIncress(){ 
    countOfOption=parseInt(countOfOption);
    countOfOption=countOfOption+1;
    console.log(countOfOption);
  }
   function countTotalOption(){
    totaloptions=totaloptions+1;
    xi=xi+1;
  }
  
  $(document).on("click",".remove_option", function(e){
      e.preventDefault();
      z=$(this).attr('data-item');
      z=parseInt(z);
      optionCountDecress();
      console.log(z);
      $('#selected_ans').val('');
      nz=z;
      if(countOfOption >= 2){ 
        for (var j = 0;j<lang_list.length;j++) { 
          lcode=lang_list[j]['code'];
         $('#'+lcode+'-option_'+z).remove();
        for (var i =z+1; i<=countOfOption; i++) {         
          $('#iscorrect_'+i).attr('data-item',nz);
          $('#'+lcode+'-rmbtn_'+i).attr('data-item',nz);
          $('#'+lcode+'-option_'+i).attr('id',lcode+'-option_'+nz);
          $('#'+lcode+'-answer_'+i).attr('id',lcode+'-answer_'+nz);
          $('#ansimg_'+i).attr('id','ansimg_'+nz);
          $('#iscorrect_'+i).attr('id','iscorrect_'+nz);
          $('#'+lcode+'-rmbtn_'+i).attr('id',lcode+'-rmbtn_'+nz);     
          $('#'+lcode+'-rm_'+i).attr('id',lcode+'-rm_'+nz);
        }

      }
    }
  })

   function optionCountDecress(){ 
    countOfOption=parseInt(countOfOption);
    countOfOption=countOfOption-1;
    console.log(countOfOption);
  }
  $(document).on("click",'.getselected',function(){
    z=$(this).attr('data-item');
    $('#selected_ans').val(z);
  })

  $(document).on('click','#savequestion',function(){

    console.log( $('#validateForm').serialize() );
    errors=validateQuestionFrom();
    //console.log(errors);
    if(errors)
      return false;
    
  })

  function validateQuestionFrom(){

    error=0;
    iscorrect='';
      q_cat=$('#select_category').val();
      q_point=$('#question_point').val();
      q_level=$('#select_level').val();
      q_durasn=$('#durations').val();
      if(q_cat==undefined || q_cat==''){
        error=1;
        $('#select_category').parent().addClass('has-error');
      }else{
        console.log("cat");
        $('#select_category').parent().removeClass('has-error');
      }
      if(q_point==undefined || q_point==''){
        error=1;
        $('#question_point').parent().addClass('has-error');
      }else{
        console.log("point");
        $('#question_point').parent().removeClass('has-error');
      }
      if(q_level==undefined || q_level==''){
        error=1;
        $('#select_level').parent().addClass('has-error');
      }else{
        console.log("leve");
        $('#select_level').parent().removeClass('has-error');
      }
      if(q_durasn==undefined || q_durasn==''){
        error=1;
        $('#durations').parent().addClass('has-error');
      }else{
        console.log("dura");
        $('#durations').parent().removeClass('has-error');
      }


      for (var j = 0;j<lang_list.length;j++) { 
          lcode=lang_list[j]['code'];
          iscorrect=$("input[name='is_correct']:checked").val();
          question=$('#'+lcode+'-question_'+j).val();
          
        for (var i =0; i<countOfOption; i++) {         
          ans=$('#'+lcode+'-answer_'+i).val();
            if(ans==undefined || ans==''){
              error=1;
              $('#'+lcode+'-answer_'+i).parent().addClass('has-error');
            }else{
              console.log("ans");
              $('#'+lcode+'-answer_'+i).parent().removeClass('has-error');
            }

        }

        if(iscorrect==undefined || iscorrect==''){
            error=1;
            $('#iscorrect_'+j).parent().addClass('has-error');
          }else{
            console.log("iscrre");
            $('#iscorrect_'+j).parent().removeClass('has-error');
          }

          if(question==undefined || question==''){
            error=1;
            $('#'+lcode+'-question_'+j).parent().addClass('has-error');
          }else{
            console.log("ques");
            $('#'+lcode+'-question_'+j).parent().removeClass('has-error');
          }

      }

      selected_ans=$('#selected_ans').val();
      console.log("selected ->>>>>>>>",selected_ans);
      if(countOfOption%2 != 0){
        error=1;
            $('#error-div').addClass('has-error');
            //$('#error-div').append("<p>Answer or Question count not correct </p>")
      }else{
            $('#error-div').removeClass('has-error');
      }
      if(iscorrect=='' || selected_ans!=iscorrect){
        error=1;
            $('#error-div').addClass('has-error');
            //$('#error-div').append("<p>Invalid selected answer.</p>")
      }else{
            $('#error-div').removeClass('has-error');
      }


      if(error==1)
       return true;
      else
        return false;

  }

  $(document).on("input",":text,select",function(){ 
    
    validateQuestionFrom()
}); 


</script>
@endsection