<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \App\Rules\MatchOldPassword;
use App\User;
use Image;
use Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        $data = User::find(Auth::id());
        return view('profile.profile',compact('data'));
    }

    public function profileUpdate(Request $request)
    {

        $validatedData = $request->validate([
            'name'         => 'required',
            'phone'         => 'required|max:12',
            'address'         => 'required',
            'profile_image'       => 'mimes:jpeg,jpg,png|max:10000',
        ],[
            "name.required" => "Name is required",
            "phone.required" => "Phone is required",
            "address.required" => "Address is required",
        ]);


        $data = User::find(Auth::id());
        if ($files = $request->file('profile_image')) {
            $files = $request->file('profile_image');
            if(\File::exists(public_path('upload/images/profile_image/'.$data->image))) {
                \File::delete(public_path('upload/images/profile_image/'.$data->image));
                \File::delete(public_path('upload/images/profile_image/thumbnail/'.$data->image));
            }
            $image = ImageResize('public/upload/images/profile_image/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->save();
        toastr()->success('Profile Updated Successfully.');
        return redirect(url(\Config::get('constants.admin_url.admin').'/profile#1'));
    }

    public function profileEmailUpdate(Request $request){
        $data = User::find(Auth::id());
        $validator = Validator::make($request->all(), [
            'new_email'         => 'required|unique:users,email,'.$data->email.',id',
            'password'         => ['required', new MatchOldPassword],
        ],[
            "new_email.required" => "Email is required",
            "password.required" => "Password is required"
        ]);

        if ($validator->fails()) {
            return redirect(url(\Config::get('constants.admin_url.admin').'/profile#2'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = User::find(Auth::id());
        $data->email = $request->new_email;
        $data->save();

        toastr()->success('Email Changed Successfully.');
        return redirect(url(\Config::get('constants.admin_url.admin').'/profile#2'));
    }

    public function profileChangePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return redirect(url(\Config::get('constants.admin_url.admin').'/profile#3'))
                        ->withErrors($validator)
                        ->withInput();
        }


        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        toastr()->success('Password Changed Successfully');
        return redirect(url(\Config::get('constants.admin_url.admin').'/profile#3'));
    }
}
