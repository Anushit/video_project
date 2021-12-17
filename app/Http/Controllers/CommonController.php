<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function ChangeStatus(Request $request){
        if ($request->ajax()) {
            $table = $request->data;
            $id = $request->id;
            
            $data = DB::table($table)->where('id', $id);
            if($data->first()->status==1){
                $status = 0;
            } else {
                $status = 1;
            }

            if($data->count() > 0) {
                $affected = \DB::table($table)->where('id', $id)->update(['status' => (int) $status]);
            }
            // toastr()->success('Status Changed');
            return true;
        }
        abort(500);
    }

    public function ChangeFeature(Request $request){
        if ($request->ajax()) {
            $table = $request->data;
            //print_r($table);die;
            $id = $request->id;
            //$id = explode(",",$request->id);
            //dd($id);
            
            $data = DB::table($table)->where('id', $id);
            //dd($data);
            if($data->first()->is_featured==2){
                $is_featured = 1;
            } else {
                $is_featured = 2;
            }

            if($data->count() > 0) {
                $affected = \DB::table($table)->where('id', $id)->update(['is_featured' => (int) $is_featured]);
            }
            // toastr()->success('Feature Changed');
            return true;
        }
        abort(500);
    }
    public function Destroy(Request $request){
        if ($request->ajax()) {
            $table = $request->data;
            // return $table;die;
            foreach ($request->check as $key => $value) {
                $affected = \DB::table($table)->where('id', $value)->update(['removed' => 'Y']);
            }
            toastr()->success('Data deleted successfully.');
            return true;
        }
        abort(500);
    }


}
