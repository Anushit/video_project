<?php

namespace App\Http\Controllers;

use App\Plans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Image;
use Hash;

class PlansController extends Controller
{    

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Plans::select('id','title','description','total_amount','plan_value','image','status')->where([['mt_plans.removed','N']]);
            return Datatables::of($data)->addColumn('checkbox', function($row){
                    $checkboxBtn = '<input type="checkbox" name="check[]" value="'.$row->id.'" class="single-check" />';
                    return $checkboxBtn;
                })->addColumn('created_at', function($row){
                    return date("Y-m-d",strtotime($row->created_at));
                 })->addColumn('image', function($row){
                    if(\File::exists(public_path('upload/images/plans/thumbnail/'.$row->image))){
                        $image = '<img src="'.asset('public/upload/images/plans/thumbnail/'.$row->image).'" class="img-circle" style="width: 40px;height:40px;">';
                    }else{
                        $image = '<img src="'.asset('public/upload/default.png').'" class="img-circle" style="width: 40px;height:40px;">';
                    }
                    return $image;
                })->addColumn('status', function($row){
                    $status = "";
                    if(!$row->status){
                        $status = "checked";
                    }
                    $status = '<div class="custom-control custom-switch">
                      <input type="checkbox" name="status[]" '.$status.' value="'.$row->status.'" class="status_change custom-control-input" data-data="mt_plans" data-id='.$row->id.' id="'.$row->id.'">
                      <label class="custom-control-label" for="'.$row->id.'"></label>
                    </div>';
                    return $status;
                })->addColumn('action', function($row) use($request){
                    $actionBtn='-';
                    if($request->user()->can('plans.edit')){
                        $actionBtn = '<a href="'.route('plans.edit',['id'=>$row->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('plans.index');
    }


   
    public function add(){
        $plans = Plans::where('removed','N')->pluck('title','id')->toArray();
        return view('plans.add',compact('plans'));
    }
    public function store(Request $request){
        $validatedData = $request->validate([
            'title'         => 'required|unique:mt_plans,title,Y,removed',
            'description'  => 'required',
            'total_amount'  => 'required|numeric',
            'plan_mode'  => 'required',
            'plan_value'  => 'required|numeric',
            'image'       => 'required|mimes:jpeg,jpg,png|max:1000000',
        ],[
            "title.required" => "title is required",
            "title.unique" => "title is already exist",
            "description.required" => "description is required",
            "total_amount.required" => "total amount is required",
            "plan_mode.required" => "plan mode is required",
            "plan_value.required" => "plan value is required",
            "image.required" => "Image is required",
            
        ]);
        $data = new Plans();
        $data->title = $request->title;
        $data->description = $request->description;
        $data->total_amount = $request->total_amount;
        $data->plan_mode = $request->plan_mode;
        $data->plan_value = $request->plan_value;
        if ($files = $request->file('image')) {
            $files = $request->file('image');
            $image = ImageResize('public/upload/images/plans/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->log_id = Auth::id();
        $data->save();
        toastr()->success('Plan added successfully.');
        return back();
    }

    public function edit($id){
        $data = Plans::find($id);
        return view('plans.edit',compact('data','id'));
    }
    public function update(Request $request,$id){
       
        $validatedData = $request->validate([
            'title'        => 'required|unique:mt_plans,title,'.$id.',id,removed,N',
            'description'  => 'required',
            'total_amount'  => 'required|numeric',
            'plan_mode'  => 'required',
            'plan_value'  => 'required|numeric',
            'image'       => 'mimes:jpeg,jpg,png|max:1000000',
            
        ],[
            "title.required" => "title is required",
            "title.unique" => "title is already exist",
            "description.required" => "description is required",
            "total_amount.required" => "total amount is required",
            "plan_mode.required" => "plan mode is required",
            "plan_value.required" => "plan value is required",
           
        ]);

        $data = Plans::find($id);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->total_amount = $request->total_amount;
        $data->plan_mode = $request->plan_mode;
        $data->plan_value = $request->plan_value;
        if ($files = $request->file('image')) {
            $files = $request->file('image');
            if(\File::exists(public_path('upload/images/plans/'.$data->image))) {
                \File::delete(public_path('upload/images/plans/'.$data->image));
                \File::delete(public_path('upload/images/plans/thumbnail/'.$data->image));
            }
            $image = ImageResize('public/upload/images/plans/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->log_id = Auth::id();
        $data->save();
        toastr()->success('Plan updated successfully.');
        return redirect(route('plans'));
    }

   

    public function titleExist(Request $request){
        if($request->title){
            if(isset($request->id) && !empty($request->id)){
            $exist = Plans::where([[\DB::raw('lower(title)'),strtolower($request->title)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = Plans::where([[\DB::raw('lower(title)'),strtolower($request->title)],['removed','N']])->get();
            }
            if($exist->count()>0){
                return json_encode('Title is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }

    
}
