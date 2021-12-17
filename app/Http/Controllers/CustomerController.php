<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\User;
use App\Roles;
use App\UserDetails;
use Image;
use Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        if ($request->ajax()) {
            $data = User::select('id','name','username','email','image','status','created_at')->where([['id','!=',Auth::id()],['id','!=','1'],['type',2],['removed','N']]);
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
                    if($request->user()->can('customers.edit')){
                        $actionBtn = '<a href="'.route('customers.edit',['id'=>$row->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('customers.index');
    }

    public function add(){
        $roles = Roles::where('removed','N')->pluck('name','id')->toArray();
        return view('customers.add',compact('roles'));
    }
    public function store(Request $request){
        
        $validatedData = $request->validate([
            'name'         => 'required',
            'username'         => 'required|unique:users,username,Y,removed',
            'email'         => 'required|unique:users,email,Y,removed',
            'phone'         => 'required|unique:users,phone,Y,removed|max:12',
            'profile_image'       => 'mimes:jpeg,jpg,png|max:10000',
            'dob'         => 'required|date',
            'doj'         => 'required|date',
        ],[
            "name.required" => "Name is required",
            "username.required" => "Username is required",
            "username.unique" => "Username is already exist",
            "email.required" => "Email is required",
            "email.unique" => "Email is already exist",
            "phone.required" => "Phone is required",
            "phone.unique" => "Mobile No. is already exist",
            "dob.required" => "DOB is required",
            "doj.required" => "DOJ is required",
        ]);

        $slug = md5($request->username.date('ymdhisa'));
        $data = new User();
        $data->name = $request->name;
        $data->slug = $slug;
        $data->type = 2;
        $data->username = $request->username;
        if ($files = $request->file('profile_image')) {
            $files = $request->file('profile_image');
            $image = ImageResize('public/upload/images/profile_image/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->password = Hash::make($request->phone);
        $data->address = $request->address;
        // print_r($data);exit;
        $data->save();

        $userDetailsData = new UserDetails();
        $userDetailsData->user_id = $data->id;
        $userDetailsData->dob = $request->dob;
        $userDetailsData->doj = $request->doj;
        $userDetailsData->save();

        toastr()->success('Customer added successfully.');
        return back();
    }
    public function edit($id){
        if(User::where([['id',$id],['type',1]])->get()->count()>0){
            abort(404);
        }
        $data = User::find($id);
        $roles = Roles::where('removed','N')->pluck('name','id')->toArray();
        return view('customers.edit',compact('roles','data','id'));
    }
    public function update(Request $request,$id){
        if(User::where([['id',$id],['type',1]])->get()->count()>0){
            abort(404);
        }
        $validatedData = $request->validate([
            'name'         => 'required',
            'username'         => 'required|unique:users,username,'.$id.',id,removed,N',
            'email'         => 'required|unique:users,email,'.$id.',id,removed,N',
            'phone'         => 'required|unique:users,phone,'.$id.',id,removed,N|max:12',
            'dob'         => 'required|date',
            'doj'         => 'required|date',
            'profile_image'       => 'mimes:jpeg,jpg,png|max:10000',
        ],[
            "name.required" => "Name is required",
            "username.required" => "Username is required",
            "username.unique" => "Username is already exist",
            "email.required" => "Email is required",
            "email.unique" => "Email is already exist",
            "phone.required" => "Phone is required",
            "phone.unique" => "Mobile No. is already exist",
            "dob.required" => "DOB is required",
            "doj.required" => "DOJ is required",
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
        $data->phone = $request->phone;
        $data->password = Hash::make($request->phone);
        $data->address = $request->address;
        $data->save();

        $user_details_id = UserDetails::where('user_id',$id)->first()->id;
        $userDetailsData = UserDetails::find($user_details_id);
        $userDetailsData->dob = $request->dob;
        $userDetailsData->doj = $request->doj;
        $userDetailsData->save();

        toastr()->success('Customer updated successfully.');
        return redirect(route('customers'));
    }
}
