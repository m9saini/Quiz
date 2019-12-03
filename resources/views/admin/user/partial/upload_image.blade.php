 <!--profile image and header image Block -->
   <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
           <label class=" control-label">{{trans('menu.sidebar.users.form.image')}} </label>
            <input type="file" name="profile_pic" id="mediaId" accept="image/*" @if(isset($user)) value="{{$user->picture_path}}" @endif onchange="after_logo_select(this.id)"  style="display:none" class="form-control onlyimageupload">
            <div class="input-group">
                <input type="text" value="@if(isset($user->image)) {{$user->image}} @endif" readonly="" id="logo-duplicate_mediaId" aria-describedby="basic-addon2" class="form-control">
                <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($user) && $user->picture_path))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
            </div>
            <div class="description"><small>Max 2Mb image size allowed. Allowed types : jpeg, png, jpg, gif.</small></div>
        </div>
         {{ Form::hidden('image',null, ['id'=>'f_mediaId']) }}
      </div><!-- col-sm-6 -->
      <!-- <div class="col-sm-6">
        <div class="form-group">
           <label class=" control-label">{{trans('menu.sidebar.users.form.cover_image')}} </label>
            <input type="file" name="profile_cover_image" id="headermediaId" accept="image/*" @if(isset($user)) value="{{$user->cover_picture_path}}" @endif onchange="after_logo_select(this.id)"  style="display:none" class="form-control">
            <div class="input-group">
                <input type="text" value="@if(isset($user->cover_image)) {{$user->cover_image}} @endif" readonly="" id="logo-duplicate_headermediaId" aria-describedby="basic-addon2" class="form-control">
                <span id="toggle_popover_headermediaId" rel="popover" class="input-group-addon btn @if(!(isset($user) && $user->cover_picture_path))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                <span onclick="document.getElementById('headermediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
            </div>
            <div class="description"><small>Max 2Mb image size allowed. Allowed types : jpeg, png, jpg, gif.</small></div>
               {{ Form::hidden('cover_image',null, ['id'=>'f_headermediaId']) }}
        </div>
      </div> --> 
    </div><!-- row -->


    <!-- Html use for Gift Images logo -->
  <div id="logo_popover_mediaId" style="display:none">
      <div id="logo_popover_content">
          @if(isset($user->image))
          <img src="{{$user->picture_path}}" class="img-thumbnail" alt="" width="304" height="236" id="logo_popover_img_mediaId" >
          @else
          <p id="logo_popover_placeholder">No media has been selected yet</p>
          @endif
      </div>
  </div> 
  <!-- Html use for Gift Images logo -->
  <div id="logo_popover_headermediaId" style="display:none">
      <div id="logo_popover_content">
          @if(isset($user->cover_image))
          <img src="{{$user->cover_picture_path}}" class="img-thumbnail" alt="" width="304" height="236" id="logo_popover_img_headermediaId" >
          @else
          <p id="logo_popover_placeholder">No media has been selected yet</p>
          @endif
      </div>
  </div>