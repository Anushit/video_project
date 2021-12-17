@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
              <li class="breadcrumb-item active">Edit</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header text-right">
                <h3 class="card-title">Edit</h3>
                <a href="{{ route('users') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                {{ Form::open(array('route' => array('users.update',$id), 'files' => true, 'method' => 'post','id'=>'usersForm')) }}
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="name">Name</label>
                      {{ Form::text('name', $data->name, array('class'=>'form-control' , 'placeholder' => 'Name','maxlength'=>100,'id'=>'name')) }}
                      <span class="text-danger">{{ $errors->first('name') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="username">Username</label>
                      {{ Form::text('username', $data->username, array('class'=>'form-control' , 'placeholder' => 'Display Name','maxlength'=>100,'id'=>'username')) }}
                      <span class="text-danger">{{ $errors->first('username') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="email">Email</label>
                      {{ Form::text('email', $data->email, array('class'=>'form-control' , 'placeholder' => 'Email','maxlength'=>100,'id'=>'email')) }}
                      <span class="text-danger">{{ $errors->first('email') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="phone">Phone</label>
                      {{ Form::text('phone', $data->phone, array('class'=>'form-control' , 'placeholder' => 'Phone','maxlength'=>100,'id'=>'phone')) }}
                      <span class="text-danger">{{ $errors->first('phone') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="phone">Profile Image</label>
                      <div class="input-group">
                        <div class=" custom-file">
                          <input name="profile_image" type="file" class="custom-file-input" id="profileImage">
                          <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                      </div>
                      <span class="text-danger">{{ $errors->first('profile_image') }}</span>
                    </div>
                      @if(\File::exists(public_path('upload/images/profile_image/thumbnail/'.$data->image)))
                        <img src="{{asset('public/upload/images/profile_image/'.$data->image)}}" class="img-circle" style="width: 100px;height: 100px;">
                      @else
                        <img src="{{asset('public/upload/default.png')}}" class="img-circle" style="width: 100px;height: 100px;">
                      @endif
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="phone">Role</label>
                      {{ Form::select('role_id',[null=>"Select Role"]+$roles,$data->role_id,array('class'=>'form-control','id'=>'role_id')) }}
                      <span class="text-danger">{{ $errors->first('role_id') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="address">Address</label>
                      {{ Form::textarea('address', $data->address, array('class'=>'form-control' , 'placeholder' => 'Address','maxlength'=>100,'rows'=>2)) }}
                      <span class="text-danger">{{ $errors->first('address') }}</span>
                    </div>
                  </div>
                  <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
                  {{ Form::close() }}
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection
@section('css')

@endsection
@section('js')
<script type="text/javascript">
  $(document).ready(function(){
    bsCustomFileInput.init();

    $('#usersForm').validate({
        rules: {
          name:{
            required:true,
            maxlength:100
          },
          username:{
            required:true,
            maxlength:100,
            remote: {
              type: 'post',
              url: '{{route("users.username-exist")}}',
              data: {
                "username": function() { return $('input[name="username"]').val(); },
                'id':'{{$id}}',
                "_token": "{{ csrf_token() }}"
              },
              dataType: 'json',
              complete: function (data) {
                $('input[type="username"]').valid();
              }
            }
          },
          email:{
            required:true,
            email:true,
            remote: {
              type: 'post',
              url: '{{route("users.email-exist")}}',
              data: {
                "email": function() { return $('input[name="email"]').val(); },
                'id':'{{$id}}',
                "_token": "{{ csrf_token() }}"
              },
              dataType: 'json',
              complete: function (data) {
                $('input[type="email"]').valid();
              }
            }
          },
          phone:{
            required:true,
            maxlength: 10,
            digits: true,
            remote: {
              type: 'post',
              url: '{{route("users.phone-exist")}}',
              data: {
                "phone": function() { return $('input[name="phone"]').val(); },
                'id':'{{$id}}',
                "_token": "{{ csrf_token() }}" 
              },
              dataType: 'json',
              complete: function (data) {
                $('input[name="phone"]').valid();
              }
            }
          },
          profile_image:{
            accept:"jpg,png,jpeg",
            filesize:1057476
          },
          role_id:{
            required:true
          }
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
  })
</script>
@endsection