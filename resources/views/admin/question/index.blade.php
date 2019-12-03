@extends('admin.layouts.master')
@section('title', " ".trans('All Questions')." - " .app_name(). " :: Admin")
@section('content')
    <section class="content-header">
      <h1><i class="fa fa-question"></i>
        {{trans('Question Management')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>

        <li class="active">{{trans('All Questions')}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Questions</h3>

         <div class="row export_filter">
            {!! Form::open(['route' => 'questions.import','id' => 'questionImportForm','files'=>true]) !!}
                <div class="col-sm-2 col-lg-2 text-right mob-text-center mb-1">
                    <label class="col-form-label" for="inputEmail3">Import Question</label>
                </div>
                <div class="col-sm-4 col-lg-4 mb-3">
                      <Input type="file" class="form-control" name="file" id="imp_question_file">
                        
                </div>
                <div class="col-sm-2 col-lg-2 mob-text-center">
                    <button type="submit" class="btn btn-primary mb-2" id="import_question">
                        Import
                    </button>
                </div>
                {!! Form::close() !!}
          </div> 
          <!-- <div class="row export_filter">
            {!! Form::open(['route' => 'export.usersReport','id' => 'reportForm','target' => '_blank']) !!}
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
                {!! Form::close() !!}
                <div class="col-sm-4 col-lg-4 mob-text-center">
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                </div>
                </div>
          </div> -->

        </div>
        <div class="box-body " style="display: block;">
            <table class="table table-bordered table-hover"  id="data_filter">
              <thead>
              <tr>
                    <th>S.No.</th>
                    <th>Question</th>
                    <th>Category</th>
                    <th>Created At</th>
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
        ajax: '{!! route('questions.ajaxdata') !!}',
        columns: [
            { data: 'rownum', name: 'rownum', searchable:false },
            { data: 'question', name: 'question' },
            { data: 'category_id', name: 'category_id' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false },
        ]
    });
  });
</script>
@endsection
