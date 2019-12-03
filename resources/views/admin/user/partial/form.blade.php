    <!--Select Role -->
   <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
             <label class="control-label">{{trans('menu.sidebar.users.form.role')}} <span class="asterisk">*</span></label>
             @php $role_id = isset($user->roles[0]) ? $user->roles[0]->name : ''; @endphp
             <span class="err_role_id">
      			 @if($role_id=='admin')
      				{{ Form::hidden('role_id','admin', []) }}
      				{!!Form::select('', getActiveRoleList(), ($role_id) ? $role_id : null , ['id'=>'select_role','placeholder'=>'Please select role','title'=>'Please choose role.', 'class' => 'form-control select2',  'required'=>'required' , ($role_id=='admin') ? 'disabled' : '' ])!!}
      			@else
      				{!!Form::select('role_id', getActiveRoleList(), ($role_id) ? $role_id : null , ['id'=>'select_role','placeholder'=>'Please select role','title'=>'Please choose role.', 'class' => 'form-control select2',  'required'=>'required' , ($role_id=='admin') ? 'disabled' : '' ])!!}
      			@endif
             </span>
        </div>
      </div><!-- col-sm-6 -->
      <div class="col-sm-6">
        <div class="form-group">
           <label class=" control-label">{{trans('menu.sidebar.users.form.full_name')}} <span class="asterisk">*</span></label>
            <span class="err_name">
              {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>'Full name','title'=>'Please enter full name.']) }}
            </span>
        </div>
      </div><!-- col-sm-6 -->
    </div>
    <!-- row -->
    <!--Username Email Block -->
   <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label">{{trans('menu.sidebar.users.form.username')}} <span class="asterisk">*</span></label>
             <span class="err_username">
              {{ Form::text('username',null, ['required','class'=>'form-control','id'=>'username','placeholder'=>'Username','title'=>'Please enter username.']) }}
            </span>
        </div>
      </div><!-- col-sm-6 -->
      <div class="col-sm-6">
        <div class="form-group">
          <label class="control-label">{{trans('menu.sidebar.users.form.email')}} <span class="asterisk">*</span></label>
          <span class="err_email">  {{ Form::email('email',null, ['required','class'=>'form-control','id'=>'email','placeholder'=>'Email','title'=>'Please enter email']) }} </span>
        </div>
      </div><!-- col-sm-6 -->
    </div><!-- row -->

   <!--Username Email Block -->
   <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label">{{trans('menu.sidebar.users.form.phone_code')}} <span class="asterisk">*</span></label>
             <span class="err_ph_country_id">
               {!!Form::select('ph_country_id', getPhoneCodeList(), (isset($user)) ? NULL : 'IN' , ['id'=>'ph_country_id','title'=>'Please select country code.', 'class' => 'form-control effect-9 select2' ])!!}
            </span>
        </div>
      </div><!-- col-sm-6 -->
       <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label">{{trans('menu.sidebar.users.form.contact_number')}} <span class="asterisk">*</span></label>
             <span class="err_phone"> {{ Form::text('phone',null, ['required','class'=>'numberonly form-control requered','id'=>'phone','placeholder'=>'Contact Number','title'=>'Please enter contact number.','maxlength'=>10]) }}</span>
            </div>
          </div><!-- col-sm-6 -->
    </div><!-- row -->

   <!--Password Block -->
 
   <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
           <label class=" control-label">{{trans('menu.sidebar.users.form.password')}} <span class="asterisk">*</span></label>
           <span class="err_password">
            <input class="form-control"  name="password"  @if(!isset($user)) required @endif title="Please enter password." value="{{ old('password') }}"  id="password" placeholder="Password" type="password">
          </span>
        </div>
      </div><!-- col-sm-6 -->
      <div class="col-sm-6">
        <div class="form-group">
          <label class=" control-label">{{trans('menu.sidebar.users.form.confirm_password')}} <span class="asterisk">*</span></label>
         <span class="err_password_confirmation">  
          <input class="form-control"  name="password_confirmation" @if(!isset($user)) required @endif title="Please re - enter password." value="{{ old('password_confirmation') }}"  id="password_confirmation" placeholder="Confirm Password" type="password">
         </span>
        </div>
      </div>
    </div>
     @include('admin.user.partial.upload_image')
@section('uniquePageScript')
@include('admin.user.partial.custom')
@endsection
