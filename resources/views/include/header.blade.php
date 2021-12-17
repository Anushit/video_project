
<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link p-0" data-toggle="dropdown" href="#">
          @if(\File::exists(public_path('upload/images/profile_image/thumbnail/'.$users_data->image)) && !empty($users_data->image) && $users_data!=null)
            {{ucfirst($users_data->name)}} <img src="{{asset('public/upload/images/profile_image/'.$users_data->image)}}" alt="User Avatar" class="img-circle" style="width: 40px;height: 40px;">
          @else
            <img src="{{asset('public/upload/default.png')}}" alt="User Avatar" class="img-circle" style="width: 40px;">
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0">
            <div class="row text-center my-2">
              <div class="col-12">
                  <a href="{{ route('profile') }}">
                    @if(\File::exists(public_path('upload/images/profile_image/thumbnail/'.$users_data->image)) && !empty($users_data->image))
                      <img src="{{ asset('public/upload/images/profile_image/'.$users_data->image) }}" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="width: 50;height: 50px;">
                    @else
                      <img src="{{ asset('public/upload/default.png') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="width: 50;height: 50px;">
                    @endif
                  </a>
              </div>
              <div class="col-12">
                  <h5 class="m-0">
                    <a class="text-dark" href="{{ route('profile') }}">{{ucfirst($users_data->name)}}</a>
                  </h5>
                  <p class="">
                    <a class="text-dark" href="{{ route('profile') }}">{{ucfirst($users_data->username)}}</a>
                  </p>

              <p class="btn btn-sm btn-danger" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">Logout</p>
              </div>
            </div>
            <!-- Message End -->
          <div class="dropdown-divider"></div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->