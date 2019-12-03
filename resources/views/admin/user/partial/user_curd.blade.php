 <div class="pull-right">
    <div class="btn-group">
      <button type="button" class="dropdown-toggle btn col-md-12  btn-primary" data-toggle="dropdown" aria-expanded="false">
          {{ trans('menu.sidebar.action') }} &nbsp;&nbsp; <i class=""> </i> <span class="caret"></span>
      </button>
      <ul class="dropdown-menu pull-right" role="menu">
        <li><a href="@if($role) {{ route('users.userCreateByRole',trim($role))}} @else {{route('users.create')}} @endif" alt="{{ trans('menu.header_buttons.head_subscription.add_new_subscription') }}"><i class="fa fa-user-plus"></i> {{trans('menu.sidebar.users.add_new')}}</a></li>
        <li class="divider"></li>
        <li><a href="@if($role) {{ route('users.activatedByRole',trim($role))}} @else {{route('users.activated')}} @endif" alt="{{ trans('menu.sidebar.users.activated') }}"> <i class="fa fa-check"></i> {{trans('menu.sidebar.users.activated')}}</a></li>
        <li><a href="@if($role) {{ route('users.inactivatedByRole',trim($role))}} @else {{route('users.inactivated')}} @endif" alt="{{ trans('menu.sidebar.users.inactivated') }}"> <i class="fa fa-times"></i> {{trans('menu.sidebar.users.suspend')}}</a></li> 
        <li><a href="@if($role) {{ route('users.deletedByRole',trim($role))}} @else {{route('users.deleted')}} @endif" alt="{{ trans('menu.sidebar.users.deleted') }}"> <i class="fa fa-trash-o"></i> {{trans('menu.sidebar.users.deleted')}}</a></li>
        
      </ul>
    </div>
</div>