  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
        <img src="{{Auth::user()->PicturePath}}" class="img-circle" alt="User Image" style="height: 41px;width: 41px;" />
        </div>
        <div class="pull-left info">
          <p>{{ucfirst(Auth::user()->name)}} <a href="javascript:;"><i class="fa fa-circle text-success"></i></a></p>
          <span>{{ucfirst(Auth::user()->roles[0]->name)}}</span>
        </div>
      </div>
      {{--
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      --}}

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">Navigation Menu</li>
        <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}"><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

        <li class="{{ Request::is('admin/users') ? 'active' : '' }} {{ Request::is('admin/users/*') ? 'active' : '' }} {{ Request::is('admin/userskyc') ? 'active' : '' }} treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>{{trans('menu.sidebar.users.main')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/users') ? 'active' : '' }}{{ Request::is('admin/users/deleted') ? 'active' : '' }}{{ Request::is('admin/users/inactivated') ? 'active' : '' }}"><a href="{{route('users.index')}}"><i class="fa fa-list"></i>{{trans('menu.sidebar.users.slug')}}</a></li>
            <li class="{{ Request::is('admin/users/create') ? 'active' : '' }}"><a href="{{route('users.create')}}" alt="{{ trans('menu.sidebar.users.add_new') }}"><i class="fa fa-plus"></i>{{trans('menu.sidebar.users.add_new')}}</a></li>
            @if( count($activeRoles)>0 )
                @foreach($activeRoles as $role)
                 <li class="{{ Request::is('admin/users/list/'.$role->name) ? 'active' : '' }} {{ Request::is('admin/users/'.$role->name.'/inactivated') ? 'active' : '' }} {{ Request::is('admin/users/'.$role->name.'/deleted') ? 'active' : '' }}"><a href="{{route('users.list',$role->name)}}" ><i class="fa fa-circle-o"></i>{{ucfirst($role->display_name)}}</a></li>
                @endforeach
            @endif
             @if(\Module::collections()->has('UsersKyc'))
            @include('userskyc::basic.menu')
          @endif
          </ul>
        </li>

        @if(\Module::collections()->has('Category'))
        @include('category::basic.menu')
      @endif

      <!-- @if(\Module::collections()->has('StaticPages'))
        @include('staticpages::basic.menu')
      @endif -->

      <li class="{{ Request::is('admin/questions') ? 'active' : '' }} {{ Request::is('admin/questions/*') ? 'active' : '' }} treeview">
        <a href="#">
          <i class="fa fa-question"></i> <span>{{trans('Question Managment')}}</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ Request::is('admin/questions') ? 'active' : '' }}{{ Request::is('admin/questions/deleted') ? 'active' : '' }}{{ Request::is('admin/questions/inactivated') ? 'active' : '' }}"><a href="{{route('questions.index')}}"><i class="fa fa-list"></i>{{trans('All Questions')}}</a></li>
          <li class="{{ Request::is('admin/questions/create') ? 'active' : '' }}"><a href="{{route('questions.create')}}" alt="{{ trans('menu.sidebar.questions.add_new') }}"><i class="fa fa-plus"></i>{{trans('Add New')}}</a></li>

        </ul>
      </li>
     
      @if(\Module::collections()->has('Tournament'))
        @include('tournament::basic.menu')
      @endif

       @if(\Module::collections()->has('Faq'))
        @include('faq::basic.menu')
      @endif
       <li class="{{ Request::is('admin/settings') ? 'active' : '' }}"><a href="{{route('backend.settings')}}"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
        <?php /*
        <li class="{{ Request::is('admin/roles') ? 'active' : '' }} {{ Request::is('admin/roles/*') ? 'active' : '' }} treeview">
          <a href="javascript:;"><i class="fa fa-lock"></i> <span>{{trans('menu.sidebar.role.manage')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           <li class="{{ Request::is('admin/roles') ? 'active' : '' }}"><a href="{{route('roles.index')}}"><i class="fa fa-list"></i> {{trans('menu.sidebar.role.slug')}}</a></li>
            <li class="{{ Request::is('admin/roles/create') ? 'active' : '' }}"><a href="{{route('roles.create')}}"><i class="fa fa-plus"></i> {{trans('menu.sidebar.role.create')}}</a></li>
          </ul>
        </li>

        <li class="{{ Request::is('admin/permission') ? 'active' : '' }} {{ Request::is('admin/permission/*') ? 'active' : '' }} treeview">
          <a href="javascript:;"><i class="fa fa-unlock"></i> <span>{{trans('menu.sidebar.permission.manage')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           <li class="{{ Request::is('admin/permission') ? 'active' : '' }}"><a href="{{route('permission.index')}}"><i class="fa fa-list"></i> {{trans('menu.sidebar.permission.slug')}}</a></li>
            <li class="{{ Request::is('admin/permission/create') ? 'active' : '' }}"><a href="{{route('permission.create')}}"><i class="fa fa-refresh"></i> {{trans('menu.sidebar.permission.refresh')}}</a></li>
          </ul>
        </li>

          <li class="{{ Request::is('admin/email-templates') ? 'active' : '' }} {{ Request::is('admin/email-templates/*') ? 'active' : '' }} treeview">
          <a href="javascript:;"><i class="fa fa-envelope"></i> <span>{{trans('menu.sidebar.email.manage')}}</span>
           <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="{{ Request::is('admin/email-templates') ? 'active' : '' }}"><a href="{{route('email-templates.index')}}"><i class="fa fa-list"></i> {{trans('menu.sidebar.email.slug')}}</a></li>
              <li class="{{ Request::is('admin/email-templates/create') ? 'active' : '' }}"><a href="{{route('email-templates.create')}}"><i class="fa fa-plus"></i> {{trans('menu.sidebar.email.create')}}</a></li>
            </ul>
        </li>

        <!-- News Manager -->
      @if(\Module::collections()->has('News'))
        <li class="{{ Request::is('admin/news') ? 'active' : '' }} {{ Request::is('admin/news/*') ? 'active' : '' }} treeview">
          <a href="javascript:;"><i class="fa fa-newspaper-o"></i> <span>{{trans('news::menu.sidebar.main')}}</span>
           <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="{{ Request::is('admin/news') ? 'active' : '' }}"><a href="{{route('news.index')}}"><i class="fa fa-list"></i> {{trans('news::menu.sidebar.slug')}}</a></li>
              <li class="{{ Request::is('admin/news/create') ? 'active' : '' }}"><a href="{{route('news.create')}}"><i class="fa fa-plus"></i> {{trans('news::menu.sidebar.create')}}</a></li>
            </ul>
        </li>
      @endif
      <!-- end news manager -->

     <!--  Partner manager -->
      @if(\Module::collections()->has('Partners'))

        <li class="{{ Request::is('admin/Partners') ? 'active' : '' }} {{ Request::is('admin/Partners/*') ? 'active' : '' }} treeview">
          <a href="javascript:;"><i class="fa fa-handshake-o"></i> <span>{{trans('partners::menu.sidebar.main')}}</span>
           <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="{{ Request::is('admin/news') ? 'active' : '' }}"><a href="{{route('partners.index')}}"><i class="fa fa-list"></i> {{trans('partners::menu.sidebar.slug')}}</a></li>
              <li class="{{ Request::is('admin/news/create') ? 'active' : '' }}"><a href="{{route('partners.create')}}"><i class="fa fa-plus"></i> {{trans('partners::menu.sidebar.create')}}</a></li>
            </ul>
        </li>
      @endif
      <!--  End Partner manager -->


      @if(\Module::collections()->has('Configuration'))
        @include('configuration::basic.menu')
      @endif

      @if(\Module::collections()->has('StaticPages'))
        @include('staticpages::basic.menu')
      @endif

      @if(\Module::collections()->has('Configuration'))
        @include('configuration::basic.menu')
      @endif

     

        @permission('log-viewer::logs.list')
          <li class="{{ Request::is('admin/log-viewer') ? 'active' : '' }} {{ Request::is('admin/log-viewer/*') ? 'active' : '' }} treeview">
            <a href="javascript:;"><i class="fa fa-eye-slash"></i> <span>Log Managment</span>
             <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
               <li class="{{ Request::is('admin/log-viewer') ? 'active' : '' }}"><a href="{{ route('log-viewer::dashboard') }}"><i class="fa fa-dashboard"></i>Log Dashboard</a></li>
                <li class="{{ Request::is('admin/log-viewer/logs') ? 'active' : '' }}"><a href="{{route('log-viewer::logs.list')}}"><i class="fa fa-list"></i> Logs</a></li>
              </ul>
          </li>
        @endpermission
        */ ?>
      </ul>
    </section>
  </aside>
