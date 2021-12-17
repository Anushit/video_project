@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>General Settings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('general.settings') }}">General Settings</a></li>
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
                <h3 class="card-title">Edit {{$data[0]->setting_name}}</h3>
                <a href="{{ route('general.settings') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                {{ Form::open(array('route' => array('general.settings.update',$id), 'files' => true, 'method' => 'post','id'=>'cmsForm')) }}
                <div class="row">
                  @foreach($data as $key=>$value)
                    <div class="col-sm-12 col-xs-12">
                      <div class="form-group">
                        @if($value->field_type=='file')
                          <label for="banner_image">{{$value->field_label}}</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input name="{{$value->field_name}}" type="file" class="custom-file-input" id="{{$value->field_name}}">
                              <label class="custom-file-label" for="{{$value->field_name}}">Choose file</label>
                            </div>
                          </div>
                          @if(\File::exists(public_path('upload/images/general_settings/'.$value->field_value)) && !empty($value->field_value))
                          <img class="profile-user-img img-fluid border-0" src="{{asset('public/upload/images/general_settings/'.$value->field_value)}}"  style="width: 100px;">
                        @endif
                        @elseif($value->field_type=='text')
                          <label for="{{$value->field_name}}">{{$value->field_label}}</label>
                          {{ Form::text($value->field_name, $value->field_value, array('class'=>'form-control' , 'placeholder' => 'Name','maxlength'=>100,'id'=>'field_name')) }}
                        @elseif($value->field_type=='textarea')
                          <label for="{{$value->field_name}}">{{$value->field_label}}</label>
                          {{ Form::textarea($value->field_name, $value->field_value, array('class'=>'form-control' , 'placeholder' => $value->field_label,'maxlength'=>5000,'rows'=>2)) }}
                        @endif
                      </div>
                    </div>
                  @endforeach
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
  });
</script>
@endsection