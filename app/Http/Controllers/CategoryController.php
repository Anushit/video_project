<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\Category;
use Image;
use Hash;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        if ($request->ajax()) {
            //print_r($request);die;
            $data = Category::select('mt_categories.*',
                \DB::raw('(CASE WHEN mt_categories.parent_id Is Not NULL THEN C2.title ELSE NULL END) AS first_title'),
                \DB::raw('(CASE WHEN C2.parent_id Is Not NULL THEN C3.title ELSE NULL END) AS second_title')
            )
           
            ->leftJoin('mt_categories as C2','C2.id','mt_categories.parent_id')
            ->leftJoin('mt_categories as C3','C3.id','C2.parent_id')
            ->where([['mt_categories.removed','N']]);
            //print_r($data);die;
            return Datatables::of($data)->addColumn('checkbox', function($row){
                    $checkboxBtn = '<input type="checkbox" name="check[]" value="'.$row->id.'" class="single-check" />';
                    return $checkboxBtn;
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
                    if(\File::exists(public_path('upload/images/categories/thumbnail/'.$row->image))){
                        $image = '<img src="'.asset('public/upload/images/categories/'.$row->image).'" class="img-circle" style="width: 40px;height:40px;">';
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
                      <input type="checkbox" name="status[]" '.$status.' value="'.$row->status.'" class="status_change custom-control-input" data-data="mt_categories" data-id='.$row->id.' id="'.$row->id.'">
                      <label class="custom-control-label" for="'.$row->id.'"></label>
                    </div>';
                    return $status;
                })->addColumn('action', function($row) use($request){
                    $actionBtn='-';
                    if($request->user()->can('categories.edit')){
                        $actionBtn = '<a href="'.route('categories.edit',['id'=>$row->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('categories.index');
    }

    public function add(){
        // $parent_categories = Category::where([['removed','N'],['status',0],['parent_id',NULL]])->pluck('title','id')->toArray();
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
        return view('categories.add',compact('parent_categories'));
    }
    public function store(Request $request){
        if(isset($request->parent_id) && !empty($request->parent_id)){
            $title_condition = 'required|unique:mt_categories,title,Y,removed,parent_id,'.$request->parent_id;
        }else{
            $title_condition = 'required|unique:mt_categories,title,Y,removed,parent_id,NULL';
        }
        $validatedData = $request->validate([
                'title'         => $title_condition,
                'description'         => 'required',
                'meta_title'         => 'required',
                'meta_keyword'         => 'required',
                'meta_description'         => 'required',
                'description'         => 'required',
                'image'       => 'required|mimes:jpeg,jpg,png|max:10000',
                'banner_image'       => 'mimes:jpeg,jpg,png|max:10000',
            ],[
                "title.required" => "Title is required",
                "title.unique" => "Title is already exist",
                "description.required" => "Description is required",
                "meta_title.required" => "Meta Title is required",
                "meta_keyword.required" => "Meta Keyword is required",
                "meta_description.required" => "Meta Description is required",
                "image.required" => "Image is required",
            ]);

        $slug = md5($request->title.date('ymdhisa'));

        $data = new Category();
        $data->parent_id = $request->parent_id;
        $data->slug = $slug;
        $data->title = $request->title;

        if ($files = $request->file('image')) {
            $files = $request->file('image');
            $image = ImageResize('public/upload/images/categories/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        if ($files = $request->file('banner_image')) {
            $files = $request->file('banner_image');
            $image = ImageResize('public/upload/images/categories/',array("height"=>50,"width"=>100),$files);
            $data->banner_image = $image;
        }

        $data->description = $request->description;
        $data->meta_title = $request->meta_title;
        $data->meta_keyword = $request->meta_keyword;
        $data->meta_description = $request->meta_description;
        $data->log_id = Auth::id();
        $data->save();
        toastr()->success('Category added successfully.');
        return back();

    }

    public function edit($id){
        $data = Category::find($id);
        // $parent_categories = Category::where([['removed','N'],['status',0],['parent_id',NULL]])->pluck('title','id')->toArray();

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
        return view('categories.edit',compact('data','id','parent_categories'));
    }

    public function update(Request $request, $id){
        if(isset($request->parent_id) && !empty($request->parent_id)){
            $title_condition = 'required|unique:mt_categories,title,'.$id.',id,removed,N,parent_id,'.$request->parent_id;
        }else{
            $title_condition = 'required|unique:mt_categories,title,'.$id.',id,removed,N,parent_id,NULL';
        }
        $validatedData = $request->validate([
                'title'         => $title_condition,
                'description'         => 'required',
                'meta_title'         => 'required',
                'meta_keyword'         => 'required',
                'meta_description'         => 'required',
                'description'         => 'required',
                'image'       => 'mimes:jpeg,jpg,png|max:10000',
                'banner_image'       => 'mimes:jpeg,jpg,png|max:10000',
            ],[
                "title.required" => "Title is required",
                "title.unique" => "Title is already exist",
                "description.required" => "Description is required",
                "meta_title.required" => "Meta Title is required",
                "meta_keyword.required" => "Meta Keyword is required",
                "meta_description.required" => "Meta Description is required",
            ]);

        $data = Category::find($id);
        $data->parent_id = $request->parent_id;
        $data->title = $request->title;

        if ($files = $request->file('image')) {
            $files = $request->file('image');
            $image = ImageResize('public/upload/images/categories/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        if ($files = $request->file('banner_image')) {
            $files = $request->file('banner_image');
            $image = ImageResize('public/upload/images/categories/',array("height"=>50,"width"=>100),$files);
            $data->banner_image = $image;
        }

        $data->description = $request->description;
        $data->meta_title = $request->meta_title;
        $data->meta_keyword = $request->meta_keyword;
        $data->meta_description = $request->meta_description;
        $data->log_id = Auth::id();
        $data->save();
        toastr()->success('Category updated successfully.');
        return redirect(route('categories'));
    }

    public function titleExist(Request $request){
        if($request->title){
            $exist = Category::where([[\DB::raw('lower(title)'),trim(strtolower($request->title))]]);
            if(isset($request->parent_id) && !empty($request->parent_id)){
                $exist = $exist->where('parent_id',$request->parent_id);
            }
            if(isset($request->id) && !empty($request->id)){
                $exist = $exist->where('id','!=',$request->id);
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
