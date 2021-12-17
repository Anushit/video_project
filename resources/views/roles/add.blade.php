@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Roles</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('roles') }}">Roles</a></li>
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
                <a href="{{ route('roles') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                {{ Form::open(array('route' => 'roles.store', 'files' => true, 'method' => 'post','id'=>'rolesForm')) }}
                @csrf
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
                      <label for="display_name">Display Name</label>
                      {{ Form::text('display_name', old('display_name'), array('class'=>'form-control' , 'placeholder' => 'Display Name','maxlength'=>100,'id'=>'display_name')) }}
                      <span class="text-danger">{{ $errors->first('display_name') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="display_name">Description</label>
                      {{ Form::textarea('description', old('description'), array('class'=>'form-control' , 'placeholder' => 'Description','maxlength'=>100,'rows'=>2)) }}
                      <span class="text-danger">{{ $errors->first('description') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="display_name">Permissions</label>
                      {{ Form::select('modules_permission[]', \Config::get('constants.modules'),json_decode(old('modules_permission[]')) ,array('class'=>'form-control select2bs4','multiple'=>true)) }}
                      <span class="text-danger">{{ $errors->first('modules_permission[]') }}</span>
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
    $('#rolesForm').validate({
        rules: {
          name:{
            required:true,
            remote: {
              type: 'post',
              url: '{{route("roles.name-exist")}}',
              data: {
                'name': $('input[type="name"]').val(),
                "_token": "{{ csrf_token() }}"
              },
              dataType: 'json',
              complete: function (data) {
                $('input[type="name"]').valid();
              }
            }
          },
          display_name:{
            required:true,
            remote: {
              type: 'post',
              url: '{{route("roles.display-name-exist")}}',
              data: {
                'name': $('input[type="display_name"]').val(),
                "_token": "{{ csrf_token() }}"
              },
              dataType: 'json',
              complete: function (data) {
                $('input[type="display_name"]').valid();
              }
            }
          },'modules_permission[]':{
            required:true
          },messages:{
            name:{
              remote:"Name"
            }
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
      });
    })
</script>
@endsection