<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
      
      @if(\File::exists(public_path('upload/images/general_settings/'.$general_settings[1]['field_value'])) && !empty($general_settings[1]['field_value']))

      <img src="{{asset('public/upload/images/general_settings/'.$general_settings[1]['field_value'])}}" alt="AdminLTE Logo" class="brand-image ml-1 img-circle elevation-3" style="opacity: .8">
      @else
      <img src="{{ asset('theme/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3 ml-1" style="opacity: .8">
      @endif
      <span class="brand-text font-weight-light">{{$general_settings[2]['field_value']}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link {{\Request::route()->getName()=='dashboard' ? __('active') : ''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
           
          @if(Gate::check('users') || Gate::check('customers'))
          <li class="nav-item {{($controller=='UsersController' || $controller=='CustomerController')? 'menu-open':''}}">
            <a href="#" class="nav-link {{($controller=='UsersController' || $controller=='CustomerController')? 'active':''}}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('users')
              <li class="nav-item">
                <a href="{{ route('users') }}" class="nav-link {{($controller=='UsersController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              @endcan
              @can('customers')
              <li class="nav-item">
                <a href="{{ route('customers') }}" class="nav-link {{($controller=='CustomerController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customers</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @endif

          @if(Gate::check('question-answer'))
          <li class="nav-item {{($controller=='QuestionAnswersController')? 'menu-open':''}}">
            <a href="#" class="nav-link {{($controller=='QuestionAnswersController')? 'active':''}}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Question-Answers
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('question-answer')
              <li class="nav-item">
                <a href="{{ route('question-answer') }}" class="nav-link {{($controller=='QuestionAnswersController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Questions</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @endif


          @if(Gate::check('general.settings'))
          <li class="nav-item {{($controller=='GeneralSettingsController')? 'menu-open':''}}">
            <a href="#" class="nav-link {{($controller=='GeneralSettingsController')? 'active':''}}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                General Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('general.settings')
              <li class="nav-item">
                <a href="{{ route('general.settings') }}" class="nav-link {{($controller=='GeneralSettingsController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Settings</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @endif
          
          @if(Gate::check('roles') || Gate::check('cms.pages') || Gate::check('categories') || Gate::check('videos') || Gate::check('banners') || Gate::check('plans'))
          <li class="nav-item {{($controller=='RolesController' || $controller=='CmsController' || $controller=='CategoryController' || $controller=='VideoController' || $controller=='BannersController' || $controller=='PlansController')? 'menu-open':''}}">
            <a href="#" class="nav-link {{($controller=='RolesController' || $controller=='CmsController'|| $controller=='CategoryController'  || $controller=='VideoController' || $controller=='BannersController' ||  $controller=='PlansController')? 'active':''}}">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Master Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('roles')
              <li class="nav-item">
                <a href="{{ route('roles') }}" class="nav-link {{($controller=='RolesController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
              @endcan
              @can('cms.pages')
              <li class="nav-item">
                <a href="{{ route('cms.pages') }}" class="nav-link {{($controller=='CmsController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cms Pages</p>
                </a>
              </li>
              @endcan
          
              @can('categories')
              <li class="nav-item">
                <a href="{{ route('categories') }}" class="nav-link {{($controller=='CategoryController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categories</p>
                </a>
              </li>
              @endcan
              @can('videos')
              <li class="nav-item">
                <a href="{{ route('videos') }}" class="nav-link {{($controller=='VideoController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Videos</p>
                </a>
              </li>
              @endcan
              @can('banners')
              <li class="nav-item">
                <a href="{{ route('banners') }}" class="nav-link {{($controller=='BannersController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Banners</p>
                </a>
              </li>
              @endcan
              @can('plans')
              <li class="nav-item">
                <a href="{{ route('plans') }}" class="nav-link {{($controller=='PlansController')? 'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Plans</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @endif
          
   
          
          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </a>
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>