<?php /* <li class="{{ Request::is('admin/userkyc') ? 'active' : '' }} {{ Request::is('admin/slider/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="fa fa-film"></i> <span>{{trans('slider::menu.sidebar.main')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
     <li class="{{ Request::is('admin/userkyc') ? 'active' : '' }}"><a href="{{route('slider.index')}}"><i class="fa fa-file"></i> {{trans('menu.sidebar.slider.slug')}}</a></li>
      
    </ul>
</li> */ ?>

<li class="{{ Request::is('admin/userkyc') ? 'active' : '' }}"><a href="{{route('userkyc.index')}}"><i class="fa fa-file"></i> {{trans('menu.sidebar.slider.slug')}}</a></li>
