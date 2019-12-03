@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.users.inactivated')." - " .app_name(). " :: Admin")
@section('content')
    <section class="content-header">
      <h1><i class="fa fa-users"></i>
        {{trans('menu.sidebar.users.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('users.index')}}">{{trans('menu.sidebar.users.slug')}}</a></li>
         @if($role)
         <li><a href="{{route('users.list',$role)}}">{{ucfirst($role)}}</a></li>
         <li class="active">{{trans('menu.sidebar.users.inactivated')}}</li>
         @else
         <li class="active">{{trans('menu.sidebar.users.inactivated')}}</li>
         @endif
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('menu.sidebar.users.inactivated')}}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            @include('admin.user.partial.user_curd')
          </div>
        </div>
        <div class="box-body " style="display: block;">
            <table class="table table-bordered table-hover"  id="data_filter">
              <thead>
              <tr>
                    <th>S.No.</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Role</th>
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
        serverSide: true,
        ajax: '{!! route('inactivated-users.ajax.data',["role"=>$role]) !!}',
        columns: [
            { data: 'rownum', name: 'rownum', searchable:false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'rolename', name: 'roles.display_name' },
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