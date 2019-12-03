@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.main')." ".trans('menu.pipe')." " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
  <h1><i class="{{trans($model.'::menu.font_icon')}} "></i>
    {{trans($model.'::menu.sidebar.main')}}
    <small></small>
  </h1>
  <ol class="breadcrumb">
     <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
     <li class="active">{{trans($model.'::menu.sidebar.slug')}}</li>
  </ol>
</section>
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">{{trans($model.'::menu.sidebar.manage')}} </h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <a href="{{route($model.'.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> {{trans($model.'::menu.sidebar.add_new')}}</a>
         <br/>
      </div>
    </div>
    <div class="box-body " style="display: block;">
        <table class="table table-bordered table-hover"  id="data_filter">
          <thead>
            <tr>
                <th>S.No.</th>
                <th>Title</th>
                <th>Value</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
          </thead>
        </table>
    </div>
  </div>
</section>
@endsection
@section('uniquePageScript')
<script>
  jQuery(document).ready(function() {
    jQuery('#data_filter').dataTable({
		sPaginationType: "full_numbers",
		processing: true,
        serverSide: false,
        ajax: "{!! route('configuration.ajaxdata') !!}",
        columns: [
            { data: 'rownum', name: 'rownum' },
            { data: 'config_title', name: 'config_title' },
            { data: 'config_value', name: 'config_value' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false },
        ]	
		
    });
	// Chosen Select
    jQuery("select").chosen({
      'min-width': '100px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    });	
  });
</script>

@endsection

