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
              <li class="breadcrumb-item active">Videos</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List</h3>
                <div class="text-right">
                  @can('videos.add')
                  <a href="{{route('videos.add')}}" class="btn bg-success">
                    <i class="fa fa-plus"></i> Add
                  </a>
                  @endcan
                  <a class="btn bg-danger delete-datas" data-data="mt_videos" >
                    <i class="fa fa-trash"></i> Delete
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="page-length-option" class="table table-bordered table-hover dataTable dtr-inline data-table nowrap" style="width: 100%">
                  <thead>
                    <tr>
                      <th class="no-sort no-search"><input type="checkbox" name="all-check" class="all-check"></th>
                      <th>User</th>
                      <th>Category</th>
                      <th>Name</th>
                      <th class="no-sort no-search">Image</th>
                      <th class="no-search">Created</th>
                      <th class="no-sort no-search">Status</th>
                      <th class="no-sort no-search">Features</th>
                      <th class="no-sort no-search">Action</th>
                      <th class="no-sort no-search">Approve/Reject</th>
                     

                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
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
@section('css')
<link rel="stylesheet" href="{{ asset('theme/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('theme/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('theme/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('js')
<script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script type="text/javascript">
     
     function approveRejectVideo($id, $is_approved){
    var message = "You want to approve this Video?"
    if($is_approved==2){
      var message = "You want to reject this Video?"
    }
    var array = {'id':$id,'is_approved':$is_approved,"_token": "{{ csrf_token() }}"};

    swal({
      title: "Are you sure?",
      text: message,
      icon: 'warning',
      dangerMode: true,
      buttons: {
        cancel: 'No',
        delete: 'Yes'
      }
        }).then(function (willDelete) {
          if (willDelete) {
             $.ajax({
                 type: "POST",
                 url: "{{route('approve-reject')}}",
                 data: array, // serializes the form's elements.
                 success: function(data)
                 {
                    location.reload();
                 }
               });
          } else {
            swal("Your imaginary data is safe", {
              title: 'Cancelled',
              icon: "error",
            });
          }
        });
  }

     
  $(document).ready(function() {       
            $('.data-table').DataTable({
                responsive: true,
                processing: true,
                autoWidth: true,
                serverSide: true,
                order: [[ 4, "desc" ]],
                lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                ajax: "{{ route('videos') }}",
                columnDefs: [{ "targets": 'no-sort',orderable: false },{ "targets": 'no-search',searchable: false }],
                columns: [
                    { "data": "checkbox" },
                    { "data": "username" },
                    { "data": "title", "name":"mt_categories.title" },
                    { "data": "name" },
                    { "data": "image" },
                    { "data": "created_at","name":"id" },
                    //{ "data": "is_featured"},
                    { "data": "status" },
                    { "data": "is_featured"}, 
                    { "data": "action" },
                    { "data": "Approve/Reject"},
                ]
            });
        });
</script>

@endsection