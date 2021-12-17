<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\User;
use App\UserDetails;
use App\Plans;
use App\Category;
use App\Video;
use App\Banners;
use App\QuestionAnswers;
use App\TransactionHistory;
use App\Notification;
use App\ForgotPasswordOtpCode;
use JWTAuth;
use Hash;
use App\Rules\MatchOldPassword;

class ProfileController extends Controller
{
    public function updateProfile(Request $request){
        $data = auth('api')->user()->toArray();
        $id = $data['id'];
        
        $validator = Validator::make($request->all(),[
            'name'         => 'required',
            'username'         => 'required|unique:users,username,'.$id.',id,removed,N',
            'email'         => 'required|unique:users,email,'.$id.',id,removed,N',
            'phone'         => 'required|max:12|unique:users,phone,'.$id.',id,removed,N',
            'profile_image'       => 'mimes:jpeg,jpg,png|max:10000',
        ],[
            "name.required" => "Full Name is required",
            "username.required" => "Username is required",
            "username.unique" => "Username is already exist",
            "email.required" => "Email is required",
            "email.unique" => "Email is already exist",
            "phone.required" => "Phone is required",
            "phone.unique" => "Phone no. is already exist",
        ]);

        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach($valid as $key => $value) {
                $arr[$key] = $value[0];
            }

            return response()->json([
                "success" => false,
                "message" => "Bad Request",
                "data" =>  $arr
            ], 400);
        }
        
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
        // $data->password = Hash::make($request->phone);
        $data->address = $request->address;
        $data->save();
        return response()->json([
                    "success" => true,
                    "message" => "Profile Updated Successfully",
                    "data" => [],
                ], 200);
    }



    public function EmailChange(Request $request){
        $data = auth('api')->user()->toArray();
        $id = $data['id'];

        $validator = Validator::make($request->all(),[
            'email'         => 'required|unique:users,email,'.$id.',id,removed,N',
            'password'         => ['required', new MatchOldPassword],
        ],[
            "email.required" => "Email is required",
            "email.unique" => "Email is already exist",
            "password.required" => "Password is required"
        ]);

        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach($valid as $key => $value) {
                $arr[$key] = $value[0];
            }

            return response()->json([
                "success" => false,
                "message" => "Bad Request",
                "data" =>  $arr
            ], 400);
        }

        $data = User::find($id);
        $data->email = $request->email;
        $data->save();

        return response()->json([
            "success" => true,
            "message" => "Email Change Successfully",
            "data" => [],
        ], 200);
    }


    public function GetPlanList(){
        $planlist = Plans::where([['removed','N'],['status',0]])->get();
        
        if($planlist->count()>0){
            $data = array();
            foreach ($planlist as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['title'] = $value->title;
                $data[$key]['description'] = $value->description;
                $data[$key]['amount_type'] = $value->amount_type;
                $data[$key]['total_amount'] = $value->total_amount;
                $data[$key]['plan_mode'] = $value->plan_mode;
                $data[$key]['plan_value'] = $value->plan_value;
                $data[$key]['type'] = $value->type;
                $data[$key]['image'] = $value->image;
                $data[$key]['status'] = $value->status;
                $data[$key]['log_id'] = $value->log_id;
                $data[$key]['removed'] = $value->removed;
                $data[$key]['created_at'] = $value->created_at;
                $data[$key]['updated_at'] = $value->updated_at    ;
            }

            return response()->json([
                    "success" => true,
                    "message" => "Api Run Successfully.",
                    "data" => $data
                ], 200);
        }else{
            return response()->json([
                    "success" => false,
                    "message" => "Record not found",
                    "data" => []
                ], 404);
        }
    }

    public function GetCategoryList(){
        $categorylist = Category::where([['removed','N'],['status',0]])->get();
        
        if($categorylist->count()>0){
            $data = array();
            foreach ($categorylist as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['parent_id'] = $value->parent_id;
                $data[$key]['slug'] = $value->slug;
                $data[$key]['title'] = $value->title;
                $data[$key]['image'] = $value->image;
                $data[$key]['banner_image'] = $value->banner_image;
                $data[$key]['description'] = $value->description;
                $data[$key]['meta_title'] = $value->meta_title;
                $data[$key]['meta_keyword'] = $value->meta_keyword;
                $data[$key]['meta_description'] = $value->meta_description;
                $data[$key]['is_featured'] = $value->is_featured;
                $data[$key]['sort_order'] = $value->sort_order;
                $data[$key]['status'] = $value->status;
                $data[$key]['log_id'] = $value->log_id;
                $data[$key]['removed'] = $value->removed;
                $data[$key]['created_at'] = $value->created_at;
                $data[$key]['updated_at'] = $value->updated_at    ;
            }

            return response()->json([
                    "success" => true,
                    "message" => "Api Run Successfully.",
                    "data" => $data
                ], 200);
        }else{
            return response()->json([
                    "success" => false,
                    "message" => "Record not found",
                    "data" => []
                ], 404);
        }
    }


    public function UploadVideo(Request $request){
       
        $data = auth('api')->user()->toArray();
        $id = $data['id'];

        $validator = Validator::make($request->all(),[
            'category_id'  => 'required',
            'name'         => 'required|unique:mt_videos,name,Y,removed',
            'description'  => 'required',
            'image'       => 'required|mimes:jpeg,jpg,png|max:10000',
            'video'       => 'required|mimes:flv,mp4,m3u8,ts,3gp,mov,avi,wmv,ogg,mkv|max:1000000',
        ]);

        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach($valid as $key => $value) {
                $arr[$key] = $value[0];
            }

            return response()->json([
                "success" => false,
                "message" => "Bad Request",
                "data" =>  $arr
            ], 400);
        }

        $slug = md5($request->name.date('ymdhisa'));
        $data = new Video();
        $data->category_id = $request->category_id;
        $data->slug = $slug;
        $data->name = $request->name;
        if ($files = $request->file('image')) {
            $files = $request->file('image');
            $image = ImageResize('public/upload/video/image/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        if ($files = $request->file('video')) {
            $files = $request->file('video');
            $video = date('YmdHis').rand(10,100).'.'.$files->getClientOriginalExtension();
            $destinationPath = 'public/upload/video/video';
            $files->move($destinationPath,$video);
            $data->video = $video;
        }
        $data->description = $request->description;
        $data->sort_order = 1;
        $data->is_featured = 2;
        $data->log_id = Auth::id();
        $data->save();

        $userDetailsData = new UserDetails();
        $userDetailsData->user_id = $data->id;
        $userDetailsData->dob = $request->dob;
        $userDetailsData->doj = $request->doj;
        $userDetailsData->save();

        return response()->json([
            "success" => true,
            "message" => "Api run Successfully.",
            "data" => []
        ], 200);
    }

    public function GetDashboard(){
        $bannerslist = Banners::where([['removed','N'],['status',0]])->get();
        $videolist = Video::where([['removed','N'],['status',0], ['is_featured',1]])->get();
       
        if($bannerslist->count()>0 || $videolist->count()>0){
            $data = array();
            $videodata = array();
            foreach ($bannerslist as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['slug'] = $value->slug;
                $data[$key]['name'] = $value->name;

                if(\File::exists(public_path('upload/images/banners/thumbnail/'.$value->image)) && !empty($value->image)){
                    $data[$key]['image'] = asset('public/upload/images/banners/'.$value->image);
                }else{
                    $data[$key]['image'] = asset('public/upload/default.png');
                }

                $data[$key]['sort_order'] = $value->sort_order;
                $data[$key]['status'] = $value->status;
                $data[$key]['log_id'] = $value->log_id;
                $data[$key]['removed'] = $value->removed;
                $data[$key]['created_at'] = $value->created_at;
                $data[$key]['updated_at'] = $value->updated_at    ;
            }

            foreach ($videolist as $video_key => $video_value) {
                $videodata[$video_key]['id'] = $video_value->id;
                $videodata[$video_key]['category_id'] = $video_value->category_id;
                $videodata[$video_key]['slug'] = $video_value->slug;
                $videodata[$video_key]['name'] = $video_value->name;

                if(\File::exists(public_path('upload/video/images/thumbnail/'.$video_value->image)) && !empty($video_value->image)){
                    $videodata[$video_key]['image'] = asset('public/upload/video/images/'.$video_value->image);
                }else{
                    $videodata[$video_key]['image'] = asset('public/upload/default.png');
                }

                if(\File::exists(public_path('upload/video/video/'.$video_value->video)) && !empty($video_value->video)){
                    $videodata[$video_key]['video'] = asset('public/upload/video/video/'.$video_value->video);
                }else{
                    $videodata[$video_key]['video'] = asset('public/upload/default.png');
                }

                $videodata[$video_key]['description'] = $video_value->description;
                $videodata[$video_key]['sort_order'] = $video_value->sort_order;
                $videodata[$video_key]['status'] = $video_value->status;
                $videodata[$video_key]['is_featured'] = $video_value->is_featured;
                $videodata[$video_key]['log_id'] = $video_value->log_id;
                $videodata[$video_key]['removed'] = $video_value->removed;
                $videodata[$video_key]['created_at'] = $video_value->created_at;
                $videodata[$video_key]['updated_at'] = $video_value->updated_at    ;
            }

            return response()->json([
                    "success" => true,
                    "message" => "Api Run Successfully.",
                    "bannerData" => $data,
                    "videoData" =>$videodata
                ], 200);
        }else{
            return response()->json([
                    "success" => false,
                    "message" => "Record not found",
                    "data" => []
                ], 404);
        }
    }


    public function GetVideoListByCategory($id){
        $videolist = Video::where([['removed','N'],['status',0], ['is_featured',1] ,['category_id',$id]])->get();

        if($videolist->count()>0){
            $data = array();
            foreach ($videolist as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['category_id'] = $value->category_id;
                $data[$key]['slug'] = $value->slug;
                $data[$key]['name'] = $value->name;

                if(\File::exists(public_path('upload/video/images/thumbnail/'.$value->image)) && !empty($value->image)){
                    $data[$key]['image'] = asset('public/upload/video/images/'.$value->image);
                }else{
                    $data[$key]['image'] = asset('public/upload/default.png');
                }

                if(\File::exists(public_path('upload/video/video/'.$value->video)) && !empty($value->video)){
                    $data[$key]['video'] = asset('public/upload/video/video/'.$value->video);
                }else{
                    $data[$key]['video'] = asset('public/upload/default.png');
                }
                
                $data[$key]['description'] = $value->description;
                $data[$key]['sort_order'] = $value->sort_order;
                $data[$key]['status'] = $value->status;
                $data[$key]['is_featured'] = $value->is_featured;
                $data[$key]['log_id'] = $value->log_id;
                $data[$key]['removed'] = $value->removed;
                $data[$key]['created_at'] = $value->created_at;
                $data[$key]['updated_at'] = $value->updated_at    ;
            }

            return response()->json([
                    "success" => true,
                    "message" => "Api Run Successfully.",
                    "data" => $data,
                ], 200);
        }else{
            return response()->json([
                    "success" => false,
                    "message" => "Record not found",
                    "data" => []
                ], 404);
        }
    }

    public function QuestionAnswer(Request $request){
        $data = auth('api')->user()->toArray();
        $id = $data['id'];
        
        $validator = Validator::make($request->all(),[
            'video_id'     => 'required',
            'description'  => 'required',
        ],[
            "video_id.required" => "Video id is required",
            "description.required" => "description is required",
        ]);

        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach($valid as $key => $value) {
                $arr[$key] = $value[0];
            }

            return response()->json([
                "success" => false,
                "message" => "Bad Request",
                "data" =>  $arr
            ], 400);
        }
        
        $data_insert = new QuestionAnswers();
        $data_insert->video_id = $request->video_id;
        $data_insert->user_id = $id;
        $data_insert->description = $request->description;
        $data_insert->log_id = Auth::id(); 
        $data_insert->save();

        return response()->json([
                    "success" => true,
                    "message" => "Question Add Successfully",
                    "data" => [],
                ], 200);
    }

    public function SubscriptionList(Request $request ){
        //print_r($request->all());die;
        $subscription = new Plans();
        if($search= $request->search){
        $subscription = Plans::where([['removed','N'],['status',0],['title','LIKE','%'.$search.'%']]);
       }
       if($request->sortBy && in_array($request->sortBy,['id','created_at'])){
           $sortBy = $request->sortBy;
       }else{
        $sortBy = 'id';
       }
       if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
        $sortOrder = $request->sortOrder;
       }else{
         $sortOrder = 'desc';
       }
       $perpage =2;
       $total = $subscription->count();
       if($request->page){
           $page = $request->page;
       }else{
           $page = 1;
       }

        $subscriptionlist = $subscription->offset(($page-1) * $perpage)->limit($perpage)->orderBy($sortBy,$sortOrder)->get();
        //print_r($subscription);die;
        if($subscriptionlist->count()>0){
            $data = array();
            foreach ($subscriptionlist as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['title'] = $value->title;
                $data[$key]['description'] = $value->description;
                $data[$key]['amount_type'] = $value->amount_type;
                $data[$key]['total_amount'] = $value->total_amount;
                $data[$key]['plan_mode'] = $value->plan_mode;
                $data[$key]['plan_value'] = $value->plan_value;
                $data[$key]['image'] = public_path($value->image);
                $data[$key]['status'] = $value->status;
                $data[$key]['log_id'] = $value->log_id;
                $data[$key]['removed'] = $value->removed;
                $data[$key]['created_at'] = $value->created_at;
                $data[$key]['updated_at'] = $value->updated_at    ;
            }

            return response()->json([
                    "success" => true,
                    "message" => "Api Run Successfully.",
                    "data" => $data,
                    "page" =>$page,
                    "total" =>$total,
                    "last_page"=> ceil($total/$perpage)
                ], 200);
        }else{
            return response()->json([
                    "success" => false,
                    "message" => "Record not found",
                    "data" => []
                ], 404);
        }
    }


    public function TransactionHistory(Request $request){
 
        $ShowTransaction = new TransactionHistory();
        if($search=$request->key){
        $ShowTransaction = TransactionHistory::where([['removed','N'],['is_active',0],['name','LIKE','%'.$search.'%']]);
        }
        if($request->sortBy && in_array($request->sortBy,['id','created_at'])){
            $sortBy = $request->sortBy;
        }else{
         $sortBy = 'id';
        }
        if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
         $sortOrder = $request->sortOrder;
        }else{
          $sortOrder = 'desc';
        }
        $perpage =5;
        $total = $ShowTransaction->count();
        if($request->page){
           $page = $request->page;
        }else{
           $page = 1;
        }
         
        $ShowTransaction = $ShowTransaction->offset(($page-1) * $perpage)->limit($perpage)->orderBy($sortBy,$sortOrder)->get();
            if($ShowTransaction->count()>0){
                $data = array();
                $userdata = array();
                foreach ($ShowTransaction as $key => $value) {
                    $data[$key]['id'] = $value->id;
                    $data[$key]['user_id'] = Auth::id();
                    $data[$key]['name'] = $value->name;
                    $data[$key]['amount'] = $value->amount;
                    $data[$key]['start_date'] = $value->start_date.date('ymdh');
                    $data[$key]['expire_date'] = $value->expire_date.date('ymdh');
                    $data[$key]['json_payment'] = $value->json_payment;
                    $data[$key]['is_active'] = $value->is_active;
                    $data[$key]['payment_transation_id'] = $value->payment_transation_id;
                    $data[$key]['purchse_date'] = $value->purchse_date.date('ymdh');
                    $data[$key]['log_id'] = $value->log_id;
                    $data[$key]['removed'] = $value->removed;
                    $data[$key]['created_at'] = $value->created_at;
                    $data[$key]['updated_at'] = $value->updated_at    ;
                }
               
    
                return response()->json([
                        "success" => true,
                        "message" => "Api Run Successfully.",
                        "data" => $data,
                        "page" =>$page,
                        "total" =>$total,
                        "last_page"=> ceil($total/$perpage)
                    ], 200);
            }else{
                return response()->json([
                        "success" => false,
                        "message" => "Record not found",
                        "data" => []
                    ], 404);
            }
        }

        public function NotificationList(Request $request){
            $Notification = new Notification();
            $data = auth('api')->user()->toArray();
            $id = $data['id'];

            if($search = $request->key){
            $Notification = Notification::where([['removed','N'],['title','LIKE','%'.$search.'%']]);
            }
            if($request->sortBy && in_array($request->sortBy,['id','created_at'])){
                $sortBy = $request->sortBy;
            }else{
             $sortBy = 'id';
            }
            if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
             $sortOrder = $request->sortOrder;
            }else{
              $sortOrder = 'desc';
            }
            $perpage =5;
            $total = $Notification->count();
            if($request->page){
               $page = $request->page;
            }else{
               $page = 1;
            }  

            $NotificationList = $Notification->offset(($page-1) * $perpage)->limit($perpage)->orderBy($sortBy,$sortOrder)->get();
                if($NotificationList->count()>0){
                    $data = array();
                    foreach ($NotificationList as $key => $value) {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['user_id'] = $id;
                        $data[$key]['title'] = $value->title;
                        $data[$key]['message'] = $value->message;
                        $data[$key]['log_id'] = $value->log_id;
                        $data[$key]['removed'] = $value->removed;
                        $data[$key]['created_at'] = $value->created_at;
                        $data[$key]['updated_at'] = $value->updated_at    ;
                    }
                   
                    return response()->json([
                            "success" => true,
                            "message" => "Api Run Successfully.",
                            "data" => $data,
                            "page" =>$page,
                            "total" =>$total,
                            "last_page"=> ceil($total/$perpage)
                        ], 200);
                }else{
                    return response()->json([
                            "success" => false,
                            "message" => "Record not found",
                            "data" => []
                        ], 404);
                }
            }

            public function QuestionAnswerList(Request $request ){
                $answerdata = new QuestionAnswers();
                if($search = $request->key){
                $answerdata = QuestionAnswers::where([['removed','N'],['description','LIKE','%'.$search.'%']]);
               }
               if($request->sortBy && in_array($request->sortBy,['id','created_at'])){
                   $sortBy = $request->sortBy;
               }else{
                $sortBy = 'id';
               }
               if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
                $sortOrder = $request->sortOrder;
               }else{
                 $sortOrder = 'desc';
               }
               $perpage =5;
               $total = $answerdata->count();
               if($request->page){
                  $page = $request->page;
               }else{
                  $page = 1;
               } 
                $answerdata = $answerdata->offset(($page-1) * $perpage)->limit($perpage)->orderBy($sortBy,$sortOrder)->get();
                if($answerdata->count()>0){
                    $data = array();
                    foreach ($answerdata as $key => $value) {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['video_id'] = $value->video_id;
                        $data[$key]['user_id'] = $value->user_id;
                        $data[$key]['description'] = $value->description;
                        $data[$key]['is_approved'] = $value->is_approved;
                        $data[$key]['approve_id'] = $value->approve_id;
                        $data[$key]['log_id'] = $value->log_id;
                        $data[$key]['removed'] = $value->removed;
                        $data[$key]['created_at'] = $value->created_at;
                        $data[$key]['updated_at'] = $value->updated_at    ;
                    }
        
                    return response()->json([
                            "success" => true,
                            "message" => "Api Run Successfully.",
                            "data" => $data,
                            "page" =>$page,
                            "total" =>$total,
                            "last_page"=> ceil($total/$perpage)
                        ], 200);
                }else{
                    return response()->json([
                            "success" => false,
                            "message" => "Record not found",
                            "data" => []
                        ], 404);
                }
            }

            public function VideoDetail(Request $request){
                $data = Video::all();
                $id = $request->id;
                $datavideo = Video::find($id);
                return response()->json([
                    "success" => true,
                    "message" => "Api run Successfully.",
                    "data" => $datavideo
                ], 200);
            }
        
}
