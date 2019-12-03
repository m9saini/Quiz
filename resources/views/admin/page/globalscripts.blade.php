<script src="{{URL::to('assets/admin/js/jquery-1.10.2.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('assets/admin/js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/jquery-migrate-1.2.1.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/jquery-ui-1.10.3.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/bootstrap.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/modernizr.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/jquery.sparkline.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/toggles.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/retina.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/jquery.cookies.js')}}"></script>

<script src="{{URL::to('assets/admin/js/flot/flot.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/flot/flot.resize.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/morris.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/raphael-2.1.0.min.js')}}"></script>

<!--<script src="{{URL::to('assets/admin/js/jquery.datatables.min.js')}}"></script>-->
<script src="{{URL::to('assets/admin/js/chosen.jquery.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/custom.js')}}"></script>
<script src="{{URL::to('assets/admin/js/jquery.validate.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/bootstrap-fileupload.min.js')}}"></script>
<!--Ck Editor -->
<script src="{{URL::to('assets/admin/js/wysihtml5-0.3.0.min.js')}}"></script>
<script src="{{URL::to('assets/admin/js/bootstrap-wysihtml5.js')}}"></script>
<script src="{{URL::to('assets/admin/js/ckeditor/ckeditor.js')}}"></script>
<script src="{{URL::to('assets/admin/js/ckeditor/adapters/jquery.js')}}"></script>

<!-- Develoer.js -->
<script src="{{URL::to('assets/admin/js/developer.js')}}"></script>
<?php /* <script src='https://www.google.com/recaptcha/api.js'></script> */ ?>

<script type="text/javascript">
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
    
function getMetaContentByName(name,content){
   var content = (content==null)?'content':content;
   return document.querySelector("meta[name='"+name+"']").getAttribute(content);
}
</script>
