@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.create')." ".trans('menu.pipe')." " .app_name(). " :: Admin")
@section('content')
   <section class="content-header">
      <h1><i class="{{trans($model.'::menu.font_icon')}}"></i>
        {{trans($model.'::menu.sidebar.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route($model.'.index')}}">{{trans($model.'::menu.sidebar.slug')}}</a></li>
         <li class="active">{{trans($model.'::menu.sidebar.create')}}</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans($model.'::menu.sidebar.create')}}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
               {!! Form::open(['route' => $model.'.store','class'=>'form-horizontal','id'=>'validateForm','files'=>true]) !!}
               <div class="panel panel-default">
                 @include($model.'::basic.form')
                  <!-- panel-body -->
                  <div class="panel-footer">
                     <div class="row">
                        <div class="col-sm-10 col-sm-offset-2">
                           <button class="btn btn-primary">{{trans('menu.sidebar.create')}}</button>
                           <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- panel -->
               {!! Form::close() !!}
               </div>
            </div>
      </div>
    </section>
@endsection
@section('uniquePageScript')
@endsection
