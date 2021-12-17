<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\User;
use App\Cms;
use App\Roles;
use Image;
use Hash;

class CmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        if ($request->ajax()) {
            $data = Cms::select('id','name','title','meta_title','status','created_at')->where([['removed','N']]);
            return Datatables::of($data)->addColumn('checkbox', function($row){
                    $checkboxBtn = '<input type="checkbox" name="check[]" value="'.$row->id.'" class="single-check" />';
                    return $checkboxBtn;
                })->addColumn('status', function($row){
                    $status = "";
                    if(!$row->status){
                        $status = "checked";
                    }
                    $status = '<div class="custom-control custom-switch">
                      <input type="checkbox" name="status[]" '.$status.' value="'.$row->status.'" class="status_change custom-control-input" data-data="cms" data-id='.$row->id.' id="'.$row->id.'">
                      <label class="custom-control-label" for="'.$row->id.'"></label>
                    </div>';
                    return $status;
                })->addColumn('action', function($row) use($request){
                    $actionBtn='-';
                    if($request->user()->can('cms.pages.edit')){
                        $actionBtn = '<a href="'.route('cms.pages.edit',['id'=>$row->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('cmspages.index');
    }

    public function edit(Request $request, $id){
        $data = Cms::find($id);
        return view('cmspages.edit',compact('id','data'));
    }
    public function update(Request $request, $id){
        $validatedData = $request->validate([
            'name'         => 'required|unique:cms,name,'.$id.',id,removed,N',
            'title'         => 'required|unique:cms,title,'.$id.',id,removed,N',
            'content'         => 'required',
            'meta_title'         => 'required',
            'meta_keyword'         => 'required',
            'meta_description'         => 'required',
            'banner_image'       => 'mimes:jpeg,jpg,png|max:10000',
        ],[
            "name.required" => "Name is required",
            "name.unique" => "Name is already exist",
            "title.required" => "Title is required",
            "title.unique" => "Title is already exist",
            "content.required" => "Content is required",
            "meta_title.required" => "Meta Title is required",
            "meta_keyword.required" => "Meta Keyword is required",
            "meta_description.required" => "Meta Description is required",
        ]);

        $data = Cms::find($id);
        $data->name = $request->name;
        $data->title = $request->title;
        $data->content = $request->content;
        $data->meta_title = $request->meta_title;
        $data->meta_keyword = $request->meta_keyword;
        $data->meta_description = $request->meta_description;
        if ($files = $request->file('banner_image')) {
            $files = $request->file('banner_image');
            if(\File::exists(public_path('upload/images/banner_image/'.$data->banner))) {
                \File::delete(public_path('upload/images/banner_image/'.$data->banner));
                \File::delete(public_path('upload/images/banner_image/thumbnail/'.$data->banner));
            }
            $image = ImageResize('public/upload/images/banner_image/',array("height"=>50,"width"=>100),$files);
            $data->banner = $image;
        }
        $data->save();

        toastr()->success($request->title.' Page updated successfully.');
        return redirect(route('cms.pages'));
    }
    public function nameExist(Request $request){
        if($request->name){
            if(isset($request->id) && !empty($request->id)){
            $exist = Cms::where([[\DB::raw('lower(name)'),strtolower($request->name)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = Cms::where([[\DB::raw('lower(name)'),strtolower($request->name)],['removed','N']])->get();
            }
            if($exist->count()>0){
                return json_encode('Name is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }
    public function titleExist(Request $request){
        if($request->title){
            if(isset($request->id) && !empty($request->id)){
            $exist = Cms::where([[\DB::raw('lower(title)'),strtolower($request->title)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = Cms::where([[\DB::raw('lower(title)'),strtolower($request->title)],['removed','N']])->get();
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
