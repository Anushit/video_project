<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\Cms;
use JWTAuth;
use DB;


class GeneralController extends Controller
{
    public function profile(){
        $data = auth('api')->user()->toArray();
        if(\File::exists(public_path('upload/images/profile_image/thumbnail/'.$data['image'])) && !empty($data['image'])){
            $data['image'] = asset('public/upload/images/profile_image/'.$data['image']);
        }else{
            $data = asset('public/upload/default.png');
        }
        return response()->json([
                    "success" => true,
                    "message" => "Api Run Successfully.",
                    "data" => $data
                ], 200);
    }
    
    public function categories(){
        $getCategory = Category::select('mt_categories.*',
                DB::raw('(CASE WHEN mt_categories.parent_id Is Not NULL THEN C2.title ELSE NULL END) AS first_title'),
                DB::raw('(CASE WHEN C2.parent_id Is Not NULL THEN C3.title ELSE NULL END) AS second_title')
            )
            ->leftJoin('mt_categories as C2','C2.id','mt_categories.parent_id')
            ->leftJoin('mt_categories as C3','C3.id','C2.parent_id')
            ->where([['mt_categories.removed','N']])
            ->where([['mt_categories.status',0]])->get();
        if($getCategory->count()>0){
            $data = array();
            foreach($getCategory as $key=>$value){
                $data[$key]['id']=$value->id;
                $data[$key]['title'] = $value->title;
                if($value->second_title!=NULL){
                    $data[$key]['title'] = $value->second_title.' >> '.$value->first_title.'>>'.$value->title;
                }
                if($value->first_title!=NULL){
                    $data[$key]['title'] = $value->first_title.' >> '.$value->title;
                }
                if(\File::exists(public_path('upload/images/categories/thumbnail/'.$value->image)) && !empty($value->image)){
                    $data[$key]['image'] = asset('public/upload/images/categories/'.$value->image);
                }else{
                    $data[$key]['image'] = asset('public/upload/default.png');
                }
                if(\File::exists(public_path('upload/images/categories/thumbnail/'.$value->banner_image)) && !empty($value->banner_image)){
                    $data[$key]['banner_image'] = asset('public/upload/images/categories/'.$value->banner_image);
                }else{
                    $data[$key]['banner_image'] = asset('public/upload/default.png');
                }
                $data[$key]['description'] = $value->description;
                $data[$key]['meta_title'] = $value->meta_title;
                $data[$key]['meta_keyword'] = $value->meta_keyword;
                $data[$key]['meta_description'] = $value->meta_description;
            }
        return response()->json([
                    "success" => true,
                    "message" => "Api Run Successfully.",
                    "data"=>$data
                ], 200);
        }else{
            return response()->json([
                    "success" => false,
                    "message" => "Record not found",
                    "data"=>[]
                ], 404);
        }
    }

    public function cmsPages(){
        //print_r('hello');exit;
        $cmsPages = Cms::where([['removed','N'],['status',0]])->get();
        if($cmsPages->count()>0){
            $data = array();
            foreach ($cmsPages as $key => $value) {
                $data[$value->id]['id'] = $value->id;
                $data[$value->id]['slug'] = $value->slug;
                $data[$value->id]['name'] = $value->name;
                $data[$value->id]['title'] = $value->title;
                $data[$value->id]['content'] = $value->content;
                $data[$value->id]['meta_title'] = $value->meta_title;
                $data[$value->id]['meta_keyword'] = $value->meta_keyword;
                $data[$value->id]['meta_description'] = $value->meta_description;
                if(\File::exists(public_path('upload/images/banner_image/thumbnail/'.$value->banner)) && !empty($value->banner)){
                    $data[$value->id]['banner'] = asset('public/upload/images/banner_image/'.$value->banner);
                }else{
                    $data[$value->id]['banner'] = asset('public/upload/default.png');
                }
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
}
