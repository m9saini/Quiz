@extends('admin.layouts.master')
@section('title', " ".trans('Settings')." - " .app_name(). " :: Admin")
@section('content')
	
	<section class="content-header">
      <h1><i class="fa fa-cogs"></i>
        {{trans('Setting Management')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> dashboard</a></li>
        <li class="active">{{trans('Settings')}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('Update Settings')}}</h3>
        </div>
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                  {!! Form::open(['route' => 'settings.store','class'=>'','id'=>'validateForm','files'=>true]) !!}

                 <div class="row">
                 	@foreach($sdata as $item)
					 <div class="col-sm-3">
				        <div class="form-group">
				           <label class=" control-label">{{$item->key_label}} <span class="asterisk">*</span></label>
				           
				             <span class="err_name">
				             @if($item->key_type=='select')
				             @php if($item->key_name=='language')
				             		$listdata=getLanguageList('list');
				             		else 
				             		$listdata=[];
				             @endphp
				             {!!Form::select($item->key_name,$listdata , $item->key_value , ['id'=>$item->key_name,'placeholder'=>'Select '.$item->key_name,'title'=>$item->key_label, 'class' => 'form-control select2'])!!}
				             @else

				             {!!Form::text($item->key_name,$item->key_value , ['id'=>$item->key_name,'placeholder'=>'Please enter '.$item->key_name.'values.','title'=>$item->key_label, 'class' => "form-control"])!!}
				             @endif
				            </span>
				        </div>
				      </div>
				    @endforeach
				</div>

                     <div class="box-footer">
                      <div id="error-div">
                      </div>
                        <div class="row">
                           <div class="col-sm-12">
                              <button class="btn btn-primary" id="savesettings">Save</button>
                              <button type="reset" class="btn btn-default">Reset</button>
                           </div>
                        </div>
                     </div>
                  {!! Form::close() !!}
               </div>
            </div>
          </div>
      </div>
    </section>
@endsection
