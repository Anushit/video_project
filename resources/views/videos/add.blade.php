@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Videos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('videos') }}">Videos</a></li>
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
                <a href="{{ route('videos') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                {{ Form::open(array('route' => 'videos.store', 'files' => true, 'method' => 'post','id'=>'videoForm')) }}
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="category_id">Categories</label>
                      {{ Form::select('category_id', [null=>"Select Category"]+$parent_categories,old('category_id'), array('class'=>'form-control' ,'id'=>'category_id')) }}
                      <span class="text-danger">{{ $errors->first('category_id') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="name">Name</label>
                      {{ Form::text('name', old('name'), array('class'=>'form-control' , 'placeholder' => 'Name','maxlength'=>100,'id'=>'name')) }}
                      <span class="text-danger">{{ $errors->first('name') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label for="description">Description</label>
                      {{ Form::textarea('description', old('description'), array('class'=>'form-control' , 'placeholder' => 'Display Name','maxlength'=>1000,'id'=>'description','rows'=>2)) }}
                      <span class="text-danger">{{ $errors->first('description') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label for="image">Image</label>
                      <div class="input-group">
                        <div class=" custom-file">
                          <input name="image" type="file" class="custom-file-input" id="image">
                          <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                      </div>
                      <span class="text-danger">{{ $errors->first('image') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label for="video">Video</label>
                      <div class="input-group">
                        <div class=" custom-file">
                          <input name="video" type="file" class="custom-file-input" id="video">
                          <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                      </div>
                      <span class="text-danger">{{ $errors->first('video') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
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

    $('#videoForm').validate({
        rules: {
          category_id:{
            required:true
          },
          name:{
            required:true,
            maxlength:100,
            remote: {
              type: 'post',
              url: '{{route("videos.title-exist")}}',
              data: { 
                "name": function() { return $('input[name="name"]').val(); },
                "category_id": function() { return $('#category_id').val(); },
                "_token": "{{ csrf_token() }}" 
              },
              dataType: 'json',
              complete: function (data) {
                $('input[type="name"]').valid();
              }
            }
          },
          description:{
            required:true,
            maxlength:1000
          },
          image:{
            required:true,
            accept:"jpg,png,jpeg",
            filesize:1000000
          },
          video:{
            required:true,
            accept:"flv,mp4,m3u8,ts,3gp,mov,avi,wmv,ogg,mkv",
            filesize:1000000
          }
        },messages: { 
          image:{
            accept:"Allowed extensions are: .jpg, .jpeg, .png",
            filesize:"Image Maximum size is 1Mb"
          },video:{
            accept:"Allowed extensions are: .flv,.mp4,.m3u8,.ts,.3gp,.mov,.avi,.wmv,.ogg,.mkv",
            filesize:"Video size should be 1Mb"
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