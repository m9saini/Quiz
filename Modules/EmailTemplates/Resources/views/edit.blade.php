@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.email.edit')." - " .app_name(). " :: Admin")
@section('content')
   <section class="content-header">
      <h1><i class="fa fa-envelope"></i>
        {{trans('menu.sidebar.email.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('email-templates.index')}}">{{trans('menu.sidebar.email.slug')}}</a></li>
         <li class="active">{{trans('menu.sidebar.email.edit')}}</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('menu.sidebar.email.edit')}}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                  {!! Form::model($data, ['method' => 'PATCH','route' => ['email-templates.update', $data->id],'class'=>'form-horizontal validate','id'=>'validateForm']) !!}
                    {{ Form::hidden('id',null, []) }}
                     @include('emailtemplates::basic.form')
                     <div class="box-footer">
                        <div class="row">
                           <div class="col-sm-12">
                               <button class="btn btn-primary">{{trans('menu.sidebar.update')}}</button>
                           <a href="{{route('email-templates.index')}}" class="btn btn-default">{{trans('menu.sidebar.cancel')}}</a>
                           </div>
                        </div>
                     </div>
                  {!! Form::close() !!}
               </div>
            </div>
      </div>
    </section>
@endsection
