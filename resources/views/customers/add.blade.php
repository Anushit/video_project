@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('customers') }}">Customers</a></li>
              <li class="breadcrumb-item active">Add</li>
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
                <h3 class="card-title">Add</h3>
                <a href="{{ route('customers') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                {{ Form::open(array('route' => 'customers.store', 'files' => true, 'method' => 'post','id'=>'usersForm')) }}
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="name">Name</label>
                      {{ Form::text('name', old('name'), array('class'=>'form-control' , 'placeholder' => 'Name','maxlength'=>100,'id'=>'name')) }}
                      <span class="text-danger">{{ $errors->first('name') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="username">Username</label>
                      {{ Form::text('username', old('username'), array('class'=>'form-control' , 'placeholder' => 'Display Name','maxlength'=>100,'id'=>'username')) }}
                      <span class="text-danger">{{ $errors->first('username') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="email">Email</label>
                      {{ Form::text('email', old('email'), array('class'=>'form-control' , 'placeholder' => 'Email','maxlength'=>100,'id'=>'email')) }}
                      <span class="text-danger">{{ $errors->first('email') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="phone">Phone</label>
                      {{ Form::text('phone', old('phone'), array('class'=>'form-control' , 'placeholder' => 'Phone','maxlength'=>100,'id'=>'phone')) }}
                      <span class="text-danger">{{ $errors->first('phone') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="dob">DOB</label>
                      {{ Form::text('dob', old('dob'), array('class'=>'form-control date-of-birth' , 'placeholder' => 'Date Of Birth','maxlength'=>100,'id'=>'dob')) }}
                      <span class="text-danger">{{ $errors->first('dob') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="doj">DOJ</label>
                      {{ Form::text('doj', old('doj'), array('class'=>'form-control date-of-join' , 'placeholder' => 'Date Of Joining','maxlength'=>100,'id'=>'doj')) }}
                      <span class="text-danger">{{ $errors->first('doj') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-xs-12">
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
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="address">Address</label>
                      {{ Form::textarea('address', old('address'), array('class'=>'form-control' , 'placeholder' => 'Address','maxlength'=>100,'rows'=>2)) }}
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
@section('js')
<script type="text/javascript">
  $(document).ready(function(){

    var startDate = "today";
    var EndDate = new Date();

    $('.date-of-birth').datepicker({
        format: 'yyyy-mm-dd',
        weekStart: 1,
        endDate: EndDate,
        autoclose: true
        })
      .on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('.date-of-join').datepicker('setStartDate', startDate);
    });
    $('.date-of-join').datepicker({
          format: 'yyyy-mm-dd',
          weekStart: 1,
          startDate: startDate,
          endDate: EndDate,
          autoclose: true
        })
      .on('changeDate', function (selected) {
        EndDate = new Date(selected.date.valueOf());
        EndDate.setDate(EndDate.getDate(new Date(selected.date.valueOf())));
    });

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
                // 'username': $('input[type="username"]').val(),
                "username": function() { return $('input[name="username"]').val(); },
                "_token": "{{ csrf_token() }}"
              },
              dataType: 'json',
              complete: function (data) {
                $('input[name="username"]').valid();
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
                "_token": "{{ csrf_token() }}"
              },
              dataType: 'json',
              complete: function (data) {
                $('input[name="email"]').valid();
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
                "_token": "{{ csrf_token() }}" 
              },
              dataType: 'json',
              complete: function (data) {
                $('input[name="phone"]').valid();
              }
            }
          },

          dob:{
            required:true
          },

          doj:{
            required:true
          },
          
          profile_image:{
            accept:"jpg,png,jpeg",
            filesize:1057476
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

    // $(".date-of-birth").datepicker({
    //     format: 'yyyy-mm-dd',
    //     autoclose: true,
    //     endDate: "today",
    // });

    // $(".date-of-birth").change(function(){
    //   $(".date-of-join").val('');
    //   var start_date = $(this).val();
    //   alert(start_date);

    //   $(".date-of-join").datepicker({
    //     format: 'yyyy-mm-dd',
    //     autoclose: true,
    //     startDate:start_date,
    //     endDate: "today",
    //   });
    // });
</script>
@endsection