@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.users.slug')." - " .app_name(). " :: Admin")
@section('content')
    <section class="content-header">
      <h1><i class="fa fa-users"></i>
        {{trans('menu.sidebar.users.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        @if($role)
        <li><a href="{{route('users.index')}}">{{trans('menu.sidebar.users.slug')}}</a></li>
        <li class="active">{{ucfirst($role)}}</li>
        @else
        <li class="active">{{trans('menu.sidebar.users.slug')}}</li>
        @endif
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Users</h3>
          
          <div class="row export_filter">
         <?php /*   {!! Form::open(['route' => 'export.usersReport','id' => 'reportForm','target' => '_blank']) !!}
                <div class="col-sm-2 col-lg-2 text-right mob-text-center mb-1">
                    <label class="col-form-label" for="inputEmail3">Export Filter:</label>
                </div>
                <div class="col-sm-4 col-lg-4 mb-3">
                      <select class="form-control" name="export_type" id="export_type">
                        <option value="">Export Type</option>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">Csv</option>
                        <option value="print">Print</option>
                     </select>
                </div>
                <div class="col-sm-2 col-lg-2 mob-text-center">
                    <button type="submit" class="btn btn-primary mb-2" id="export_report">
                        Export
                    </button>
                </div>
                {!! Form::close() !!} */ ?>
                <div class="col-sm-12 col-lg-12 mob-text-center">
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  @include('admin.user.partial.user_curd')
                </div>
                </div>
          </div> 
          
        </div>
        <div class="box-body " style="display: block;">
            <table class="table table-bordered table-hover"  id="data_filter">
              <thead>
              <tr>
                    <th>S.No.</th>
                    <th>User Name</th>
                    <th data-priority="1">Email</th>
                    <th data-priority="1">Phone</th>
                   <!--  <th>Available</th> -->
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
        ajax: '{!! route('users.ajaxdata',["role"=>$role]) !!}',
        columns: [
            { data: 'rownum', name: 'rownum', searchable:false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            /*{ data: 'available', name: 'available',orderable:false,searchable:false},*/
            { data: 'rolename', name: 'rolename'},
            { data: 'action', name: 'action', orderable:false, searchable:false },    
        ]
    });
  });
</script>
@endsection
