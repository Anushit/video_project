<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\GeneralSettings;
use Image;
use Hash;

class GeneralSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        if ($request->ajax()) {
            $data = GeneralSettings::select('setting_type','setting_name')->where([['removed','N']])->groupBy('setting_type','setting_name');
            return Datatables::of($data)->addColumn('checkbox', function($row){
                    $checkboxBtn = '1';
                    return $checkboxBtn;
                })->addColumn('action', function($row) use($request){
                    $actionBtn='-';
                    if($request->user()->can('general.settings.edit')){
                        $actionBtn = '<a href="'.route('general.settings.edit',['id'=>$row->setting_type]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('generalsettings.index');
    }

    public function edit($id){
        $data = GeneralSettings::where('setting_type',$id)->get();
        return view('generalsettings.edit',compact('data','id'));
    }

    public function update(Request $request,$id){
        $data = GeneralSettings::where('setting_type',$id)->get();
        foreach($data as $key=>$value){
            if($value->field_type=='file'){
                if ($request->file($value->field_name)!=null) {
                    if(\File::exists(public_path('upload/images/general_settings/'.$value->field_value))) {
                        \File::delete(public_path('upload/images/general_settings/'.$value->field_value));
                        \File::delete(public_path('upload/images/general_settings/thumbnail/'.$value->field_value));
                    }
                    $files = $request->file($value->field_name);
                    $image = ImageResize('public/upload/images/general_settings/',array("height"=>50,"width"=>100),$files);
                    $upd_data = GeneralSettings::find($value->id);
                    $upd_data->field_value=$image;
                    $upd_data->save();
                }
            }else{
                $upd_data = GeneralSettings::find($value->id);
                $upd_data->field_value=$request[$value->field_name];
                $upd_data->save();
            }
        }
        toastr()->success('Settings updated successfully.');
        return redirect(route('general.settings'));
    }
}
