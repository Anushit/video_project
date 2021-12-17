<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\Category;
use App\Video;
use Image;
use Hash;

class VideoController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
       
        if ($request->ajax()) {
            $data = Video::select( 'mt_videos.*','users.name as username','mt_categories.title',
                \DB::raw('(CASE WHEN mt_categories.parent_id Is Not NULL THEN C2.title ELSE NULL END) AS first_title'),
                \DB::raw('(CASE WHEN C2.parent_id Is Not NULL THEN C3.title ELSE NULL END) AS second_title'),'users.name as username'
            )
            ->leftJoin('mt_categories','mt_categories.id','mt_videos.category_id')
            ->leftJoin('mt_categories as C2','C2.id','mt_categories.parent_id')
            ->leftJoin('mt_categories as C3','C3.id','C2.parent_id')
            ->leftJoin('users','users.id','mt_videos.user_id')
            ->where([['mt_videos.removed','N']]);
            return Datatables::of($data)->addColumn('checkbox', function($row){
                    $checkboxBtn = '<input type="checkbox" name="check[]" value="'.$row->id.'" class="single-check" />';
                    return $checkboxBtn;
                })->addColumn('username', function($row){
                    return $row->username;
                })->editColumn('title', function($row){
                    $title = $row->title;
                    if($row->second_title!=NULL){
                        $title = $row->second_title.' >> '.$row->first_title.'>>'.$row->title;
                    }
                    if($row->first_title!=NULL){
                        $title = $row->first_title.' >> '.$row->title;
                    }
                    return $title;
                })->addColumn('image', function($row){
                    if(\File::exists(public_path('upload/video/images/thumbnail/'.$row->image))){
                        $image = '<img src="'.asset('public/upload/video/images/thumbnail/'.$row->image).'" class="img-circle" style="width: 40px;height:40px;">';
                    }else{
                        $image = '<img src="'.asset('public/upload/default.png').'" class="img-circle" style="width: 40px;height:40px;">';
                    }
                    return $image;
                })->addColumn('created_at', function($row){
                    return date("Y-m-d",strtotime($row->created_at));
                })->addColumn('is_featured', function($row){
                    $is_featured = "";
                    if($row->is_featured==1){
                        $is_featured = "checked";
                    }
                    $is_featured = '<div class="custom-control custom-switch">
                      <input type="checkbox" name="is_featured[]" '.$is_featured.' value="'.$row->is_featured.'" class="feature_change custom-control-input" data-data="mt_videos" sid="'.$row->id.'" data-id=test'.$row->id.' id="test'.$row->id.'" >
                      <label class="custom-control-label" for="test'.$row->id.'"></label>
                    </div>';
                    return $is_featured;
                })->addColumn('status', function($row){
                    $status = "";
                    if(!$row->status){
                        $status = "checked";
                    }
                    $status = '<div class="custom-control custom-switch">
                      <input type="checkbox" name="status[]" '.$status.' value="'.$row->status.'" class="status_change custom-control-input" data-data="mt_videos" data-id='.$row->id.' id="'.$row->id.'">
                      <label class="custom-control-label" for="'.$row->id.'"></label>
                    </div>';
                    return $status;
                })->addColumn('action', function($row) use($request){
                    $actionBtn='-';
                    if($request->user()->can('videos.edit')){
                        $actionBtn = '<a href="'.route('videos.edit',['id'=>$row->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->addColumn('Approve/Reject', function($row) use($request){
                    if($row->type==1){
                     $actionBtn = "";
                    }else{
                    if($row->is_approved!=0){
                        if($row->is_approved==1){
                            $actionBtn='<span class="badge bg-success">Approved</span>';
                        }else{
                            $actionBtn='<span class="badge bg-danger">Rejected</span>';
                        }
                    }else{
                        $actionBtn = '<span onclick="approveRejectVideo('.$row->id.',1)" style="cursor:pointer;" class="badge bg-success">Approve</span><br><span onclick="approveRejectVideo('.$row->id.',2)" style="cursor:pointer;" class="badge bg-danger">Reject</span>';
                    }
                    }
                    return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('videos.index');

    }
    

    public function add(){
        $getCategory = Category::select('mt_categories.*',
                \DB::raw('(CASE WHEN mt_categories.parent_id Is Not NULL THEN C2.title ELSE NULL END) AS first_title'),
                \DB::raw('(CASE WHEN C2.parent_id Is Not NULL THEN C3.title ELSE NULL END) AS second_title')
            )
            ->leftJoin('mt_categories as C2','C2.id','mt_categories.parent_id')
            ->leftJoin('mt_categories as C3','C3.id','C2.parent_id')
            ->where([['mt_categories.removed','N']])->orderby('mt_categories.id','desc')->get();
        $parent_categories = array();
        if($getCategory->count()>0){
            foreach($getCategory as $value){
                $parent_categories[$value->id] = $value->title;
                if($value->second_title!=NULL){
                    $parent_categories[$value->id] = $value->second_title.' >> '.$value->first_title.'>>'.$value->title;
                }
                if($value->first_title!=NULL){
                    $parent_categories[$value->id] = $value->first_title.' >> '.$value->title;
                }
            }
        }
        return view('videos.add',compact('parent_categories'));
    }
    public function store(Request $request){
        
        if(isset($request->category_id) && !empty($request->category_id)){
        $title_condition = 'required|unique:mt_videos,name,Y,removed,category_id,'.$request->category_id;
        }else{
        $title_condition = 'required|unique:mt_videos,name,Y,removed,category_id,NULL';
        }
        $validatedData = $request->validate([
                'category_id'  => 'required',
                'name'         => $title_condition,
                'description'  => 'required',
                'image'       => 'required|mimes:jpeg,jpg,png|max:1000000',
                'video'       => 'required|mimes:flv,mp4,m3u8,ts,3gp,mov,avi,wmv,ogg,mkv|max:1000000',
            ],[
                "category_id.required" => "Category is required",
                "name.required" => "Name is required",
                "name.unique" => "Name is already exist",
                "description.required" => "Description is required",
                "image.required" => "Image is required",
                "video.required" => "Video Should Be 1Mb",
            ]);
        $slug = md5($request->name.date('ymdhisa'));

        $data = new Video();
        $data->user_id = Auth::id();
        $data->category_id = $request->category_id;
        $data->slug = $slug;
        $data->name = $request->name;

        if ($files = $request->file('image')) {
            $files = $request->file('image');
            $image = ImageResize('public/upload/video/images/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        if ($files = $request->file('video')) {
            $files = $request->file('video');
            $name = date('YmdHis').rand(10,100).'.'.$files->getClientOriginalExtension();
            $destinationPath = 'public/upload/video/video';
            $files->move($destinationPath,$name);
            $data->video = $name;
        }

        $data->description = $request->description;
        $data->log_id = Auth::id();
        $data->save();
        toastr()->success('Video added successfully.');
        return back();

    }

    public function edit($id){
        $data = Video::find($id);
        $parent_categories = Category::where([['removed','N'],['status',0],['parent_id',NULL]])->pluck('title','id')->toArray();

        $getCategory = Category::select('mt_categories.*',
                \DB::raw('(CASE WHEN mt_categories.parent_id Is Not NULL THEN C2.title ELSE NULL END) AS first_title'),
                \DB::raw('(CASE WHEN C2.parent_id Is Not NULL THEN C3.title ELSE NULL END) AS second_title')
            )
            ->leftJoin('mt_categories as C2','C2.id','mt_categories.parent_id')
            ->leftJoin('mt_categories as C3','C3.id','C2.parent_id')
            ->where([['mt_categories.removed','N']])->orderby('mt_categories.id','desc')->get();
        $parent_categories = array();
        if($getCategory->count()>0){
            foreach($getCategory as $value){
                $parent_categories[$value->id] = $value->title;
                if($value->second_title!=NULL){
                    $parent_categories[$value->id] = $value->second_title.' >> '.$value->first_title.'>>'.$value->title;
                }
                if($value->first_title!=NULL){
                    $parent_categories[$value->id] = $value->first_title.' >> '.$value->title;
                }
            }
        }
        return view('videos.edit',compact('data','id','parent_categories'));
    }

    public function update(Request $request, $id){
        if(isset($request->category_id) && !empty($request->category_id)){
            $title_condition = 'required|unique:mt_videos,name,'.$id.',id,removed,N,category_id,'.$request->category_id;
        }else{
            $title_condition = 'required|unique:mt_videos,name,'.$id.',id,removed,N,category_id,NULL';
        }
        $validatedData = $request->validate([
                'name'         => $title_condition,
                'description'  => 'required',
                'image'        => 'mimes:jpeg,jpg,png|max:1000000',
                'video'        => 'mimes:flv,mp4,m3u8,ts,3gp,mov,avi,wmv,ogg,mkv|max:1000000',
            ],[
                "name.required" => "Name is required",
                "name.unique"   => "Name is already exist",
                "description.required" => "Description is required",
                
            ]);

            $slug = md5($request->name.date('ymdhisa'));

            $data = Video::find($id);
            $data->category_id = $request->category_id;
            $data->slug = $slug;
            $data->name = $request->name;
            if ($files = $request->file('image')) {
                $files = $request->file('image');
                if(\File::exists(public_path('upload/video/images/'.$data->image))) {
                    \File::delete(public_path('upload/video/images/'.$data->image));
                    \File::delete(public_path('upload/video/images/thumbnail/'.$data->image));
                }
                $image = ImageResize('public/upload/video/images/',array("height"=>50,"width"=>100),$files);
                $data->image = $image;
            }
            if ($files = $request->file('video')) {
                $files = $request->file('video');
                if(\File::exists(public_path('upload/video/video/'.$data->video))) {
                    \File::delete(public_path('upload/video/video/'.$data->video));
                    \File::delete(public_path('upload/video/video/thumbnail/'.$data->video));
                }
                $name = date('YmdHis').rand(10,100).'.'.$files->getClientOriginalExtension();
                $destinationPath = 'public/upload/video/video';
                $files->move($destinationPath,$name);
                $data->video = $name;
            }
        $data->description = $request->description;
        $data->log_id = Auth::id();
        $data->save();
        toastr()->success('Videos updated successfully.');
        return redirect(route('videos'));
    }

    public function titleExist(Request $request){
        if($request->name){
            $exist = Video::where([[\DB::raw('lower(name)'),trim(strtolower($request->name))]]);
            if(isset($request->category_id) && !empty($request->category_id)){
                $exist = $exist->where('category_id',$request->category_id);
            }
            if(isset($request->id) && !empty($request->id)){
                $exist = $exist->where('id','!=',$request->id);
            }
            if($exist->count()>0){
                return json_encode('Name is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }

    public function videoApproveReject(Request $request){
        $id = $request->id;
        $is_approved = $request->is_approved;
            
        $updVideo = Video::find($id);
        
        $updVideo->is_approved = $is_approved;
        $updVideo->approve_id = Auth::id();
        $updVideo->log_id = Auth::id();
        $updVideo->save();


        return true;
    }
        

}
