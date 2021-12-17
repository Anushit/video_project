@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('categories') }}">Categories</a></li>
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
                <a href="{{ route('categories') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                {{ Form::open(array('route' => 'categories.store', 'files' => true, 'method' => 'post','id'=>'categoryForm')) }}
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="parent_id">Categories</label>
                      {{ Form::select('parent_id', [null=>"Select Category"]+$parent_categories,old('parent_id'), array('class'=>'form-control' ,'id'=>'parent_id')) }}
                      <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="title">Title</label>
                      {{ Form::text('title', old('title'), array('class'=>'form-control cat-title' , 'placeholder' => 'Title','maxlength'=>100,'id'=>'title')) }}
                      <span class="text-danger">{{ $errors->first('title') }}</span>
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
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="meta_title">Meta Title</label>
                      {{ Form::text('meta_title', old('meta_title'), array('class'=>'form-control' , 'placeholder' => 'Meta Title','maxlength'=>100,'id'=>'meta_title')) }}
                      <span class="text-danger">{{ $errors->first('meta_title') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="meta_title">Meta Keyword</label>
                      {{ Form::text('meta_keyword', old('meta_keyword'), array('class'=>'form-control' , 'placeholder' => 'Meta Title','maxlength'=>100,'id'=>'meta_keyword')) }}
                      <span class="text-danger">{{ $errors->first('meta_keyword') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label for="meta_description">Meta Description</label>
                      {{ Form::textarea('meta_description', old('meta_description'), array('class'=>'form-control' , 'placeholder' => 'Display Name','maxlength'=>1000,'id'=>'meta_description','rows'=>2)) }}
                      <span class="text-danger">{{ $errors->first('meta_description') }}</span>
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
                      <label for="banner_image">Banner Image</label>
                      <div class="input-group">
                        <div class=" custom-file">
                          <input name="banner_image" type="file" class="custom-file-input" id="banner_image">
                          <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                      </div>
                      <span class="text-danger">{{ $errors->first('banner_image') }}</span>
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

    $('#categoryForm').validate({
        rules: {
          title:{
            required:true,
            maxlength:100,
            remote: {
              type: 'post',
              url: '{{route("categories.title-exist")}}',
              data: { 
                "title": function() { return $('input[name="title"]').val(); },
                "parent_id": function() { return $('#parent_id').val(); },
                "_token": "{{ csrf_token() }}" 
              },
              dataType: 'json',
              complete: function (data) {
                $('input[type="title"]').valid();
              }
            }
          },
          description:{
            required:true,
            maxlength:1000
          },
          meta_title:{
            required:true,
            maxlength:100
          },
          meta_keyword:{
            required:true,
            maxlength:100
          },
          meta_description:{
            required:true,
            maxlength:1000
          },
          image:{
            required:true,
            accept:"jpg,png,jpeg",
            filesize:1057476
          },
          banner_image:{
            accept:"jpg,png,jpeg",
            filesize:1057476
          }
        },messages: { 
          image:{
            accept:"Allowed extensions are: .jpg, .jpeg, .png",
            filesize:"Image Maximum size is 10mb"
          },banner_image:{
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