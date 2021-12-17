@extends('include.app')
@section('content')
<div class="content-wrapper" style="min-height: 1299.69px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Questions-Answers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Questions-Answers</li>
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
              <div class="card-header">
                <h3 class="card-title">List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="page-length-option" class="table table-bordered table-hover dataTable dtr-inline data-table nowrap" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#ID</th>
                      <th>User</th>
                      <th>video</th>
                      <th class="no-search">Created</th>
                      <th class="no-sort no-search">Status</th>
                      <th class="no-sort no-search">Action</th>
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



<!-- model -->
<div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
        <form id="post-form" method="post" action="{{ route('question-answer.save-form') }}">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reply to a question</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="container">
                <div  class="wrapper" style="height: 300px; overflow-x: hidden">
                    <div id="append_data">
                    </div>
                </div>
            </div>
              
            
            <div class="modal-body">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="reply">Reply *</label>
                  <textarea class="form-control" placeholder="Reply" maxlength="100" rows="2" name="reply" cols="50"></textarea>
                  <span class="text-danger"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" id="send_form"  class="btn btn-primary">Submit</button>
            </div>
          <!-- /.modal-content -->
        </div>
       </form>
        <!-- /.modal-dialog -->
      </div>
<!-- model end -->


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
  $(document).ready(function() {
            $('.data-table').DataTable({
                responsive: true,
                processing: true,
                autoWidth: true,
                serverSide: true,
                order: [[ 3, "desc" ]],
                lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                ajax: "{{ route('question-answer') }}",
                columnDefs: [{ "targets": 'no-sort',orderable: false },{ "targets": 'no-search',searchable: false }],
                columns: [
                    { "data": "id" },
                    { "data": "username" },
                    { "data": "video_name" },
                    { "data": "created_at","user_id":"id" },
                    { "data": "status" },
                    { "data": "action" }
                ]
            });
        });


  function approveRejectVideo($id, $is_approved){
    var message = "You want to approve this Data?"
    if($is_approved==2){
      var message = "You want to reject this Data?"
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
                 url: "{{route('question-answer.video-approve-reject')}}",
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

  function videoReply($id){
    var id = $id;
    $.ajax({
        url: '{{route("question-answer.get-answerlist")}}',
        type:'GET',
        data: { id:id },
        success: function(data) {
          var html = '';
            if($.isEmptyObject(data.error)){
                $.each(data, function(index, value) {
                html += '<div class="row">\n\
                            <div class="col-md-6">\n\
                                <label>Approved By : </label> '+value.name+'\n\
                            </div>\n\
                            <div class="col-md-6">\n\
                                <label>Approved By : </label> '+ value.created_at+'\n\
                            </div>\n\
                            <div class="col-md-12">\n\
                                <label>Reply : </label> '+value.description+'\n\
                            </div>\n\
                        </div><hr>'; 
                });
                $(".modal").modal();
                $("#append_data").append(html);
            }else{
                printErrorMsg(data.error);
            }
        }
    });

    $("#send_form").click(function(e){
        e.preventDefault();
        var _token = $("input[name='_token']").val();
        var id = $id;
        var reply = $("textarea[name='reply']").val();
        $.ajax({
            url: '{{route("question-answer.save-form")}}',
            type:'POST',
            data: {_token:_token, id:id, reply:reply},
            success: function(data) {
              if($.isEmptyObject(data.error)){
                  location.reload();
              }else{
                  printErrorMsg(data.error);
              }
            }
        });
    });

  }
</script>


@endsection




 

