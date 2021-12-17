@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>CMS Pages</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('cms.pages') }}">CMS Pages</a></li>
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
                <a href="{{ route('customers') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                {{ Form::open(array('route' => array('cms.pages.update',$id), 'files' => true, 'method' => 'post','id'=>'cmsForm')) }}
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
                      <label for="title">Title</label>
                      {{ Form::text('title',$data->title , array('class'=>'form-control' , 'placeholder' => 'Display Name','maxlength'=>100,'id'=>'title')) }}
                      <span class="text-danger">{{ $errors->first('title') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="content">Content</label>
                      {{ Form::textarea('content', $data->content , array('class'=>'form-control' , 'placeholder' => 'content','maxlength'=>5000,'rows'=>2)) }}
                      <span class="text-danger">{{ $errors->first('content') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="meta_title">Meta Title</label>
                      {{ Form::text('meta_title', $data->meta_title, array('class'=>'form-control' , 'placeholder' => 'Meta Title','maxlength'=>100,'id'=>'meta_title')) }}
                      <span class="text-danger">{{ $errors->first('meta_title') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="meta_keyword">Meta Keyword</label>
                      {{ Form::text('meta_keyword',$data->meta_keyword , array('class'=>'form-control' , 'placeholder' => 'Meta Keyword','maxlength'=>100,'id'=>'meta_keyword')) }}
                      <span class="text-danger">{{ $errors->first('meta_keyword') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="meta_description">Meta Description</label>
                      {{ Form::textarea('meta_description', $data->meta_description, array('class'=>'form-control' , 'placeholder' => 'Meta Description','maxlength'=>5000,'rows'=>2)) }}
                      <span class="text-danger">{{ $errors->first('meta_description') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <label for="banner_image">Banner Image</label>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="custom-file">
                          <input name="banner_image" type="file" class="custom-file-input" id="bannerImage">
                          <label class="custom-file-label" for="banner_image">Choose file</label>
                        </div>
                      </div>
                      <span class="text-danger">{{ $errors->first('banner_image') }}</span>
                    </div>
                    @if(\File::exists(public_path('upload/images/banner_image/thumbnail/'.$data->banner)) && !empty($data->banner))
                      <img class="profile-user-img img-fluid border-0" src="{{asset('public/upload/images/banner_image/'.$data->banner)}}"  style="width: 200px;">
                    @endif
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
<script src="{{ asset('theme/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
  CKEDITOR.config.removePlugins = 'newpage,exportpdf,print,save';
  CKEDITOR.replace('content',{
    removeButtons: 'About',
  });

  CKEDITOR.config.allowedContent = true;
  $(document).ready(function(){
    bsCustomFileInput.init();

    $('#cmsForm').validate({
        ignore: [],
        rules: {
          name:{
            required:true,
            maxlength:100,
            remote: {
              type: 'post',
              url: '{{route("cms.pages.name-exist")}}',
              data: {
                'name': $('input[type="name"]').val(),
                'id':'{{$id}}',
                "_token": "{{ csrf_token() }}"
              },
              dataType: 'json',
              complete: function (data) {
                $('input[type="name"]').valid();
              }
            }
          },
          title:{
            required:true,
            maxlength:100,
            remote: {
              type: 'post',
              url: '{{route("cms.pages.title-exist")}}',
              data: {
                'title': $('input[type="title"]').val(),
                'id':'{{$id}}',
                "_token": "{{ csrf_token() }}"
              },
              dataType: 'json',
              complete: function (data) {
                $('input[type="title"]').valid();
              }
            }
          },
          content:{
            required:true,
            maxlength:5000
          },
          meta_title:{
            required:true,
            maxlength:200
          },
          meta_keyword:{
            required:true,
            maxlength:200
          },
          meta_description:{
            required:true,
            maxlength:5000
          },
          banner_image:{
            accept:"jpg,png,jpeg",
            filesize:1057476
          }
        },messages: { 
          banner_image:{
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