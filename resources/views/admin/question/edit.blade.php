@extends('admin.layouts.master')
@section('title', " ".trans('Edit Question')." - " .app_name(). " :: Admin")
@section('content')
   <section class="content-header">
      <h1><i class="fa fa-question"></i>
        {{trans('Question Management')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('questions.index')}}">{{trans('All Questions')}}</a></li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('Edit Question')}}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                 {!! Form::model($model,['method'=>'PATCH', 'route' => ['questions.update',$model->id],'class'=>'','id'=>'validateForm','files'=>true]) !!}

                   @include('admin.question.form',['data'=>$model])

                     <div class="box-footer">
                      <div id="error-div"></div>
                        <div class="row">
                           <div class="col-sm-12">
                             <button class="btn btn-primary" id="savequestion">{{trans('menu.sidebar.update')}}</button>
                            <a href="{{route('questions.index')}}" class="btn btn-default">{{trans('menu.sidebar.cancel')}}</a>
                           </div>
                        </div>
                     </div>
                  {!! Form::close() !!}
               </div>
            </div>
      </div>
    </section>
@endsection


