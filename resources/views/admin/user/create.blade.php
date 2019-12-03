@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.users.create')." - " .app_name(). " :: Admin")
@section('content')
   <section class="content-header">
      <h1><i class="fa fa-users"></i>
        {{trans('menu.sidebar.users.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('users.index')}}">{{trans('menu.sidebar.users.slug')}}</a></li>
          @if($role_id)
         <li><a href="{{route('users.list',$role_id)}}">{{ucfirst($role_id)}}</a></li>
         <li class="active">{{trans('menu.sidebar.users.create')}}</li>
         @else
         <li class="active">{{trans('menu.sidebar.users.create')}}</li>
         @endif
      </ol>
   </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('menu.sidebar.users.create')}}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                  {!! Form::open(['route' => 'users.store','class'=>'','id'=>'validateForm','files'=>true]) !!}
                    @include('admin.user.partial.form')
                     <div class="box-footer">
                        <div class="row">
                           <div class="col-sm-12">
                              <button class="btn btn-primary">{{trans('menu.sidebar.create')}}</button>
                              <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
                           </div>
                        </div>
                     </div>
                  {!! Form::close() !!}
               </div>
            </div>
      </div>
    </section>
@endsection
@section('uniquePageScript')
@endsection