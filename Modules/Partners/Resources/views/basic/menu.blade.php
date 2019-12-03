<li class="{{ Request::is('admin/slider') ? 'active' : '' }} {{ Request::is('admin/slider/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="fa fa-film"></i> <span>{{trans('slider::menu.sidebar.main')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
     <li class="{{ Request::is('admin/slider') ? 'active' : '' }}"><a href="{{route('slider.index')}}"><i class="fa fa-list"></i> {{trans('menu.sidebar.slider.slug')}}</a></li>
      <li class="{{ Request::is('admin/slider/create') ? 'active' : '' }}"><a href="{{route('slider.create')}}"><i class="fa fa-plus"></i> {{trans('menu.sidebar.slider.create')}}</a></li>
    </ul>
</li>
