<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\User;
use App\Roles;
use Image;
use Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        if ($request->ajax()) {
            $data = User::select('id','name','username','email','image','status','created_at')->where([['id','!=',Auth::id()],['id','!=','1'],['type',1],['removed','N']]);
            return Datatables::of($data)->addColumn('checkbox', function($row){
                    $checkboxBtn = '<input type="checkbox" name="check[]" value="'.$row->id.'" class="single-check" />';
                    return $checkboxBtn;
                })->addColumn('image', function($row){
                    if(\File::exists(public_path('upload/images/profile_image/thumbnail/'.$row->image))){
                        $image = '<img src="'.asset('public/upload/images/profile_image/'.$row->image).'" class="img-circle" style="width: 40px;height:40px;">';
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
                      <input type="checkbox" name="status[]" '.$status.' value="'.$row->status.'" class="status_change custom-control-input" data-data="users" data-id='.$row->id.' id="'.$row->id.'">
                      <label class="custom-control-label" for="'.$row->id.'"></label>
                    </div>';
                    return $status;
                })->addColumn('action', function($row) use($request){
                    $actionBtn='-';
                    if($request->user()->can('users.edit')){
                        $actionBtn = '<a href="'.route('users.edit',['id'=>$row->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('users.index');
    }

    public function add(){
        $roles = Roles::where('removed','N')->pluck('name','id')->toArray();
        return view('users.add',compact('roles'));
    }
    public function store(Request $request){
        $validatedData = $request->validate([
            'name'         => 'required',
            'role_id'         => 'required',
            'username'         => 'required|unique:users,username,Y,removed',
            'email'         => 'required|unique:users,email,Y,removed',
            'phone'         => 'required|unique:users,phone,Y,removed|max:12',
            'profile_image'       => 'mimes:jpeg,jpg,png|max:10000',
        ],[
            "name.required" => "Name is required",
            "role_id.required" => "Role is required",
            "username.required" => "Username is required",
            "username.unique" => "Username is already exist",
            "email.required" => "Email is required",
            "email.unique" => "Email is already exist",
            "phone.required" => "Phone is required",
            "phone.unique" => "Mobile No. is already exist",
        ]);

        $slug = md5($request->username.date('ymdhisa'));
        $data = new User();
        $data->name = $request->name;
        $data->slug = $slug;
        $data->username = $request->username;
        if ($files = $request->file('profile_image')) {
            $files = $request->file('profile_image');
            // if(\File::exists(public_path('upload/images/profile_image/'.$data->image))) {
            //     \File::delete(public_path('upload/images/profile_image/'.$data->image));
            //     \File::delete(public_path('upload/images/profile_image/thumbnail/'.$data->image));
            // }
            $image = ImageResize('public/upload/images/profile_image/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->email = $request->email;
        $data->role_id = $request->role_id;
        $data->phone = $request->phone;
        $data->password = Hash::make($request->phone);
        $data->address = $request->address;
        $data->save();
        toastr()->success('User added successfully.');
        return back();
    }
    public function edit($id){
        if(User::where([['id',$id],['type',2]])->get()->count()>0){
            abort(404);
        }
        $data = User::find($id);
        $roles = Roles::where('removed','N')->pluck('name','id')->toArray();
        return view('users.edit',compact('roles','data','id'));
    }
    public function update(Request $request,$id){
        if(User::where([['id',$id],['type',2]])->get()->count()>0){
            abort(404);
        }
        $validatedData = $request->validate([
            'name'         => 'required',
            'role_id'         => 'required',
            'username'         => 'required|unique:users,username,'.$id.',id,removed,N',
            'email'         => 'required|unique:users,email,'.$id.',id,removed,N',
            'phone'         => 'required|unique:users,phone,'.$id.',id,removed,N|max:12',
            'profile_image'       => 'mimes:jpeg,jpg,png|max:10000',
        ],[
            "name.required" => "Name is required",
            "role_id.required" => "Role is required",
            "username.required" => "Username is required",
            "username.unique" => "Username is already exist",
            "email.required" => "Email is required",
            "email.unique" => "Email is already exist",
            "phone.required" => "Phone is required",
            "phone.unique" => "Mobile No. is already exist",
        ]);

        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        if ($files = $request->file('profile_image')) {
            $files = $request->file('profile_image');
            if(\File::exists(public_path('upload/images/profile_image/'.$data->image))) {
                \File::delete(public_path('upload/images/profile_image/'.$data->image));
                \File::delete(public_path('upload/images/profile_image/thumbnail/'.$data->image));
            }
            $image = ImageResize('public/upload/images/profile_image/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->email = $request->email;
        $data->role_id = $request->role_id;
        $data->phone = $request->phone;
        $data->password = Hash::make($request->phone);
        $data->address = $request->address;
        $data->save();
        toastr()->success('User updated successfully.');
        return redirect(route('users'));
    }



    public function userNameExist(Request $request){
        if($request->username){
            if(isset($request->id) && !empty($request->id)){
            $exist = User::where([[\DB::raw('lower(username)'),strtolower($request->username)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = User::where([[\DB::raw('lower(username)'),strtolower($request->username)],['removed','N']])->get();
            }
            if($exist->count()>0){
                return json_encode('Username is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }
    public function emailExist(Request $request){
        if($request->email){
            if(isset($request->id) && !empty($request->id)){
            $exist = User::where([[\DB::raw('lower(email)'),strtolower($request->email)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = User::where([[\DB::raw('lower(email)'),strtolower($request->email)],['removed','N']])->get();
            }
            if($exist->count()>0){
                return json_encode('Email is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }

    public function phoneExist(Request $request){
        if($request->phone){
            if(isset($request->id) && !empty($request->id)){
            $exist = User::where([[\DB::raw('phone'),strtolower($request->phone)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = User::where([[\DB::raw('phone'),strtolower($request->phone)],['removed','N']])->get();
            }
            if($exist->count()>0){
                return json_encode('Mobile No. is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }
}
