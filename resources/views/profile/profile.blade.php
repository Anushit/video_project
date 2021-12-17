@extends('include.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  @if(\File::exists(public_path('upload/images/profile_image/thumbnail/'.$data->image)) && !empty($data->image))
                  <img class="profile-user-img img-fluid img-circle border-0" src="{{asset('public/upload/images/profile_image/'.$data->image)}}" alt="User profile picture" style="width: 100;height: 100px;">
                  @else
                  <img class="profile-user-img img-fluid img-circle border-0" src="{{asset('public/upload/default.png')}}" alt="User profile picture" style="width: 100;height: 100px;">

                  @endif
                </div>

                <h3 class="profile-username text-center">{{$data->name}}</h3>
                <p class="text-muted text-center m-0">{{$data->username}}</p>
                <p class="text-center m-0">{{$data->email}}</p>
                <p class="text-center m-0">{{$data->phone}}</p>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" id="profile-tab" href="#profile" data-toggle="tab">Profile</a></li>
                  <li class="nav-item"><a class="nav-link" id="emailChange-tab" href="#emailChange" data-toggle="tab">Email Change</a></li>
                  <li class="nav-item"><a class="nav-link" id="changePassword-tab" href="#changePassword" data-toggle="tab">Change Password</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="profile">
                    <form id="profileForm" class="form-horizontal" action="{{route('profile.update')}}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class=" row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10 form-group">
                          <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="{{$data->name}}">
                          <small class="errorTxt1">
                          <div id="uname-error" class="error text-red">{{ $errors->first('name') }}</div>
                        </small>
                        </div>
                        
                      </div>
                      <div class="row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10 form-group">
                          <input type="text" name="phone" class="form-control numbers" id="phone" placeholder="Phone" value="{{$data->phone}}">
                          <small class="errorTxt1">
                            <div id="uname-error" class="error text-red">{{ $errors->first('phone') }}</div>
                          </small>
                        </div>
                        
                      </div>
                      <div class="row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Profile Image</label>
                        <div class="col-sm-10 form-group">
                          <div class="input-group">
                            <div class="custom-file">
                              <input name="profile_image" type="file" class="custom-file-input" id="profileImage">
                              <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                          </div>
                          <small class="errorTxt1">
                          <div id="uname-error" class="error text-red">{{ $errors->first('profile_image') }}</div>
                        </small>
                        </div>
                      </div>
                      <div class="row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10 form-group">
                          <textarea name="address" class="form-control" id="address" placeholder="Experience">{{$data->address}}</textarea>
                          <small class="errorTxt1">
                            <div id="uname-error" class="error text-red">{{ $errors->first('address') }}</div>
                          </small>
                        </div>
                        
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="emailChange">
                    <form id="emailChangeForm" class="form-horizontal" action="{{route('profile.email.update')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                      @csrf
                      <div class=" row">
                        <label for="new_email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10 form-group">
                          <input type="text" class="form-control" id="new_email" placeholder="Add New Email" name="new_email" value="{{old('new_email')}}">
                          <small class="errorTxt1">
                          <div id="uname-error" class="error text-red">{{ $errors->first('new_email') }}</div>
                        </small>
                        </div>
                        
                      </div>
                      <div class="row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10 form-group">
                          <input type="password" name="password" class="form-control password" id="password" placeholder="Confirm Password">
                          <small class="errorTxt1">
                            <div id="uname-error" class="error text-red">{{ $errors->first('password') }}</div>
                          </small>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="changePassword">
                    <form id="changePasswordForm" class="form-horizontal" action="{{route('profile.change.password')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                      @csrf
                      <div class="row">
                        <label for="old_password" class="col-sm-2 col-form-label">Old Password</label>
                        <div class="col-sm-10 form-group">
                          <input type="password" name="old_password" class="form-control" id="old_password" placeholder="Old Password" maxlength="50">
                          <small class="errorTxt1">
                            <div id="uname-error" class="error text-red">{{ $errors->first('old_password') }}</div>
                          </small>
                        </div>
                      </div>

                      <div class="row">
                        <label for="new_password" class="col-sm-2 col-form-label">New Password</label>
                        <div class="col-sm-10 form-group">
                          <input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password" maxlength="50">
                          <small class="errorTxt1">
                            <div id="uname-error" class="error text-red">{{ $errors->first('new_password') }}</div>
                          </small>
                        </div>
                      </div>

                      <div class="row">
                        <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10 form-group">
                          <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" maxlength="50">
                          <small class="errorTxt1">
                            <div id="uname-error" class="error text-red">{{ $errors->first('confirm_password') }}</div>
                          </small>
                        </div>
                      </div>

                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
  </div>

@endsection

@section('js')
<script type="text/javascript">
  $(function () {
    bsCustomFileInput.init();
    var hash = window.location.hash;
    if(hash=='#1'){
      $("#profile-tab").trigger("click");
    }if(hash=='#2'){
      $("#emailChange-tab").trigger("click");
    }if(hash=='#3'){
      $("#changePassword-tab").trigger("click");
    }

    $('#profileForm').validate({
      rules: {
        name:{
          required:true
        },
        phone: {
          required: true,
          maxlength: 10,
          digits: true
        },
        profile_image:{
          accept:"jpg,png,jpeg",
          filesize:1057476
        },
        address:{
          required:true
        },
      },messages: { 
        profile_image:{
          accept:"Allowed extensions are: .jpg, .jpeg, .png",
          filesize:"Image Maximum size is 10mb"
        } 
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    $('#emailChangeForm').validate({
      rules: {
        new_email:{
          required:true,
          email: true,
        },
        password: {
          required: true,
          minlength: 8
        }
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    })

    $('#changePasswordForm').validate({
      rules: {
        old_password: {
          required: true,
          minlength: 8
        },
        new_password: {
          required: true,
          minlength: 8
        },
        confirm_password: {
          required: true,
          minlength: 8,
          equalTo:"#new_password"
        },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    })
  });
</script>
@endsection