@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Banners</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('banners') }}">Banners</a></li>
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
                <a href="{{ route('banners') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                {{ Form::open(array('route' => 'banners.store', 'files' => true, 'method' => 'post','id'=>'bannersForm')) }}
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
                      <label for="phone">Image</label>
                      <div class="input-group">
                        <div class=" custom-file">
                          <input name="image" type="file" class="custom-file-input" id="Image">
                          <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                      </div>
                      <span class="text-danger">{{ $errors->first('image') }}</span>
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
    bsCustomFileInput.init();

    $('#bannersForm').validate({
        rules: {
          name:{
            required:true,
            maxlength:100,
            remote: {
              type: 'post',
              url: '{{route("banners.name-exist")}}',
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
          image:{
            required:true,
            accept:"jpg,png,jpeg",
            filesize:1057476
          },
        },messages: { 
          image:{
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