<li class="{{ Request::is('admin/category') ? 'active' : '' }} {{ Request::is('admin/category/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="fa fa-film"></i> <span>{{trans('category::menu.sidebar.main')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
     <li class="{{ Request::is('admin/category') ? 'active' : '' }}"><a href="{{route('category.index')}}"><i class="fa fa-list"></i> {{trans('menu.sidebar.category.slug')}}</a></li>
      <li class="{{ Request::is('admin/category/create') ? 'active' : '' }}"><a href="{{route('category.create')}}"><i class="fa fa-plus"></i> {{trans('menu.sidebar.category.create')}}</a></li>
    </ul>
</li>
