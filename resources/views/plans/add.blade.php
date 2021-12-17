@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Plans</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('plans') }}">Plans</a></li>
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
                <a href="{{ route('plans') }}" class="btn bg-secondary">
                  <i class="fa fa-list"></i>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                {{ Form::open(array('route' => 'plans.store', 'files' => true, 'method' => 'post','id'=>'plansForm')) }}
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="name">Title</label>
                      {{ Form::text('title', old('title'), array('class'=>'form-control' , 'placeholder' => 'Title','maxlength'=>100,'id'=>'title')) }}
                      <span class="text-danger">{{ $errors->first('title') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="description">Description</label>
                      {{ Form::text('description', old('description'), array('class'=>'form-control' , 'placeholder' => 'Description','maxlength'=>100,'id'=>'description')) }}
                      <span class="text-danger">{{ $errors->first('description') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="name">Total Amount</label>
                      {{ Form::text('total_amount', old('total_amount'), array('class'=>'form-control numbers-decimal' , 'placeholder' => 'total amount','maxlength'=>100,'id'=>'total_amount')) }}
                      <span class="text-danger">{{ $errors->first('total_amount') }}</span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                    <label for="phone">Plan Mode</label>
                    {{ Form::select('plan_mode',[null=>"Select Mode"]+Config::get('constants.plan_mode') ,old('plan_mode'), array('class'=>'form-control' ,'id'=>'plan_mode')) }}
                      <span class="text-danger">{{ $errors->first('plan_mode') }}</span>
                    </div>
                </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="name">Plan Value</label>
                      {{ Form::text('plan_value', old('plan_value'), array('class'=>'form-control' , 'placeholder' => 'Plan Value','maxlength'=>100,'id'=>'plan_value')) }}
                      <span class="text-danger">{{ $errors->first('plan_value') }}</span>
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
                </div>
                <div class="row">
                
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

    $('#plansForm').validate({
        rules: {
          title:{
            required:true,
            maxlength:100,
            remote: {
              type: 'post',
              url: '{{route("plans.title-exist")}}',
              data: {
                'title': $('input[type="title"]').val(),
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
            
          },
          total_amount:{
            required:true,
            number:true,   
          },
          plan_mode:{
            required:true, 
          },
          plan_value:{
            required:true,
            number:true,    
          },
          amount_type:{
            required:true, 
          },
          image:{
            required:true,
            accept:"jpg,png,jpeg",
            filesize:1000000
          },
         
        },messages: { 
          image:{
            accept:"Allowed extensions are: .jpg, .jpeg, .png",
            filesize:"Image Maximum size is 1Mb"
          },
          total_amount:{
              required: "Enter  Amount",
              number:  "Decimal Numbers Only",
          },
          plan_value:{
              required: "Enter Plan Value",
              number:  "Numbers Only",
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