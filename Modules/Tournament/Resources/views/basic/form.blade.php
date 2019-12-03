  <link rel="stylesheet" href="{{URL::to('node_modules/admin-lte/plugins/timepicker/bootstrap-timepicker.min.css')}}">
@include($model.'::basic.common',compact('model','data','catList','weekdayfield'))


@section('uniquePageScript')
 <script src="{{URL::to('node_modules/admin-lte/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script type="text/javascript">
    $( ".timepicker" ).timepicker({
    	 showInputs: false
    }); 

    $(document).on('change','#game_shedule',function(){
    	val=$(this).val();
    	if(val=='w'){
    		$('#weekday').val('');
    		$('#weekdaydiv').show();
    	}else{
    		$('#weekday').val('');
    		$('#weekdaydiv').hide();
    	}
    })

   /* $(document).on('click','#savetournament',function(){

    console.log( $('#validateForm').serialize() );
    gsh=$('#game_shedule').val()
    if(gsh!='' && gsh=='w'){
    	wday=$('#weekday').val()
    	if(wday=='')
    	{
    		$('#weekday').parent().addClass('has-error');
    		return false;
    	}else{
    		$('#weekday').parent().removeClass('has-error');
    	}
    }    
  })*/
</script>
@endsection