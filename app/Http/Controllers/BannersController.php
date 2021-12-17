<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\Banners;
use Image;
use Hash;

class BannersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Banners::select('id','name','image','created_at','status')->where([['banners.removed','N']]);
            return Datatables::of($data)->addColumn('checkbox', function($row){
                    $checkboxBtn = '<input type="checkbox" name="check[]" value="'.$row->id.'" class="single-check" />';
                    return $checkboxBtn;
                })->addColumn('image', function($row){
                    if(\File::exists(public_path('upload/images/banners/thumbnail/'.$row->image))){
                        $image = '<img src="'.asset('public/upload/images/banners/thumbnail/'.$row->image).'" class="img-circle" style="width: 40px;height:40px;">';
                    }else{
                        $image = '<img src="'.asset('public/upload/default.png').'" class="img-circle" style="width: 40px;height:40px;">';
                    }
                    return $image;
                })->addColumn('created_at', function($row){
                    return date("Y-m-d",strtotime($row->created_at));
                })->addColumn('status', function($row){
                    $status = "";
                    if(!$row->status){
                        $status = "checked";
                    }
                    $status = '<div class="custom-control custom-switch">
                      <input type="checkbox" name="status[]" '.$status.' value="'.$row->status.'" class="status_change custom-control-input" data-data="banners" data-id='.$row->id.' id="'.$row->id.'">
                      <label class="custom-control-label" for="'.$row->id.'"></label>
                    </div>';
                    return $status;
                })->addColumn('action', function($row) use($request){
                    $actionBtn='-';
                    if($request->user()->can('banners.edit')){
                        $actionBtn = '<a href="'.route('banners.edit',['id'=>$row->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('banners.index');
    }

    public function add(){
        $banners = Banners::where('removed','N')->pluck('name','id')->toArray();
        return view('banners.add',compact('banners'));
    }
    public function store(Request $request){
        $validatedData = $request->validate([
            'name'         => 'required|unique:banners,name,Y,removed',
            'image'       => 'required|mimes:jpeg,jpg,png|max:1000000',
        ],[
            "name.required" => "Name is required",
            "name.unique" => "Name is already exist",
            "image.required" => "Image is required",
            
        ]);
        $data = new Banners();
        $data->name = $request->name;
        if ($files = $request->file('image')) {
            $files = $request->file('image');
            $image = ImageResize('public/upload/images/banners/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->log_id = Auth::id();
        $data->save();
        toastr()->success('Banner added successfully.');
        return back();
    }

    public function edit($id){
        $data = Banners::find($id);
        return view('banners.edit',compact('data','id'));
    }
    public function update(Request $request,$id){
       
        $validatedData = $request->validate([
            'name'        => 'required|unique:banners,name,'.$id.',id,removed,N',
            'image'       => 'mimes:jpeg,jpg,png|max:1000000',
          
            
        ],[
            "name.required" => "Name is required",
            "name.unique" => "Name is already exist",
        ]);

        $data = Banners::find($id);
        $data->name = $request->name;
        if ($files = $request->file('image')) {
            $files = $request->file('image');
            if(\File::exists(public_path('upload/images/banners/'.$data->image))) {
                \File::delete(public_path('upload/images/banners/'.$data->image));
                \File::delete(public_path('upload/images/banners/thumbnail/'.$data->image));
            }
            $image = ImageResize('public/upload/images/banners/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->log_id = Auth::id();
        $data->save();
        toastr()->success('Banner updated successfully.');
        return redirect(route('banners'));
    }

    public function nameExist(Request $request){
        if($request->name){
            if(isset($request->id) && !empty($request->id)){
            $exist = Banners::where([[\DB::raw('lower(name)'),strtolower($request->name)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = Banners::where([[\DB::raw('lower(name)'),strtolower($request->name)],['removed','N']])->get();
            }
            if($exist->count()>0){
                return json_encode('Name is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }

    

}
