<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\QuestionAnswers;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Hash;
use DB;


class QuestionAnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   
    public function index(Request $request){
        if ($request->ajax()) {
            $data = QuestionAnswers::select( 'question_answers.*','mt_videos.name as video_name','users.name as username'
                )
            ->leftJoin('mt_videos','mt_videos.id','question_answers.video_id')
            ->leftJoin('users','users.id','question_answers.user_id')
            ->where([['question_answers.removed','N'],['question_answers.parent_id',0]]);
            // print_r($data);exit;
            return Datatables::of($data)->addColumn('id', function($row){
                    return $row->id;
                })->addColumn('username', function($row){
                    return $row->username;
                })->addColumn('video_name', function($row){
                    return $row->video_name;
                })->addColumn('created_at', function($row){
                    return date("Y-m-d",strtotime($row->created_at));
                })->addColumn('status', function($row){
                    $status = "";
                    if(!$row->status){
                        $status = "checked";
                    }
                    $status = '<div class="custom-control custom-switch">
                      <input type="checkbox" name="status[]" '.$status.' value="'.$row->status.'" class="status_change custom-control-input" data-data="tr_employee_leaves" data-id='.$row->id.' id="'.$row->id.'">
                      <label class="custom-control-label" for="'.$row->id.'"></label>
                    </div>';
                    return $status;
                })->addColumn('action', function($row) use($request){
                    if($row->is_approved!=0){
                        if($row->is_approved==1){
                            $actionBtn='<button type="button" class="badge bg-dark" onclick="videoReply('.$row->id.')">Reply</button>';
                        }else{
                            $actionBtn='<span class="badge bg-danger">Rejected</span>';
                        }
                    }else{
                        $actionBtn = '<span onclick="approveRejectVideo('.$row->id.',1)" style="cursor:pointer;" class="badge bg-success">Approve</span><br><span onclick="approveRejectVideo('.$row->id.',2)" style="cursor:pointer;" class="badge bg-danger">Reject</span>';
                    }
                    return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('questionsansewer.index');

    }


    public function videoApproveReject(Request $request){
        $id = $request->id;
        $is_approved = $request->is_approved;
            
        $updVideo = QuestionAnswers::find($id);
        
        $updVideo->is_approved = $is_approved;
        $updVideo->approve_id = Auth::id();
        $updVideo->log_id = Auth::id();
        $updVideo->save();


        return true;
    }

    public function saveForm(Request $request){
        $record_id = $request->id;

        $updVideo = QuestionAnswers::find($record_id);

        $data = new QuestionAnswers();
        $data->video_id = $updVideo->video_id;
        $data->user_id = $updVideo->user_id;
        $data->parent_id = Auth::id();
        $data->description = $request->reply;
        $data->is_approved = $updVideo->is_approved;
        $data->approve_id = $updVideo->approve_id;
        $data->log_id = Auth::id();
        $data->save();

        toastr()->success('Reply send successfully.');
        return $data;
    }


    public function GetAnswerList(Request $request){
        
        $id = $request->id;
        $updVideo = QuestionAnswers::find($id);
        
        $data = QuestionAnswers::select('question_answers.*', 'users.name')->leftJoin('users','users.id','question_answers.parent_id')->where([['question_answers.removed', 'N'],['question_answers.parent_id', '!=', '0'],['question_answers.video_id', $updVideo->video_id]])->get()->toArray();
        
        return $data;
    }
}
