<li class="{{ Request::is('admin/tournament') ? 'active' : '' }} {{ Request::is('admin/tournament/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="fa fa-trophy"></i> <span>{{trans('tournament::menu.sidebar.main')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/tournament') ? 'active' : '' }}"><a href="{{route('tournament.index')}}"><i class="fa fa-list"></i> {{trans('tournament::menu.sidebar.slug')}}</a></li>
    <li class="{{ Request::is('admin/tournament/create') ? 'active' : '' }}"><a href="{{route('tournament.create')}}"><i class="fa fa-plus"></i> {{trans('tournament::menu.sidebar.create')}}</a></li>
  </ul>
</li>