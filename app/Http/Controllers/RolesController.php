<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\Roles;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        if ($request->ajax()) {
            $data = Roles::select('id','name','display_name','description');
            return Datatables::of($data)->addColumn('checkbox', function($row){
                    $checkboxBtn = '<label class="mt-3"><input type="checkbox" name="check[]" value="'.$row->id.'" class="single-check" /><span><i class="material-icons vertical-align-bottom blue-grey-text text-lighten-5"> </i></span></label>';
                    return $checkboxBtn;
                })->addColumn('action', function($row) use ($request){
                    $actionBtn='-';
                    if($request->user()->can('roles.edit')){
                        $actionBtn = '<a href="'.route('roles.edit',['id'=>$row->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                    }
                return $actionBtn;
                })->escapeColumns([])->make(true);
        }
        return view('roles.index');
    }
    public function add(){
        return view('roles.add');
    }
    public function store(Request $request){
        $validatedData = $request->validate([
            'name'         => 'required|unique:roles,name,Y,removed',
            'display_name'         => 'required|unique:roles,display_name,Y,removed',
            'modules_permission.*'=>'required'
        ],[
            'name.required' => "Name is required",
            'name.unique' => "Name is already exist",
            'display_name.required' => "Display Name is required",
            'display_name.unique' => "Display Name is already exist",
            'modules_permission.*.required' => "Permission is required.",
        ]);
        $data = new Roles();
        $data->name = $request->name;
        $data->display_name = $request->display_name;
        $data->description = $request->description;
        $data->modules_permission = json_encode($request->modules_permission);
        $data->log_id = Auth::id();
        $data->save();

        toastr()->success('Role added successfully.');
        return back();
    }

    public function edit(Request $request, $id){
        $data = Roles::find($id);
        return view('roles.edit',compact('data','id'));
    }

    public function update (Request $request, $id) {
        $validatedData = $request->validate([
            'name'         => 'required|unique:roles,name,'.$id.',id,removed,N',
            'display_name' => 'required|unique:roles,display_name,'.$id.',id,removed,N',
            'modules_permission.*'=>'required'
        ],[
            'name.required' => "Name is required",
            'name.unique' => "Name is already exist",
            'display_name.required' => "Display Name is required",
            'display_name.unique' => "Display Name is already exist",
            'modules_permission.*.required' => "Permission is required.",
        ]);

        $data = Roles::find($id);
        $data->name = $request->name;
        $data->display_name = $request->display_name;
        $data->description = $request->description;
        $data->modules_permission = json_encode($request->modules_permission);
        $data->log_id = Auth::id();
        $data->save();

        toastr()->success('Roles updated successfully.');
        return redirect(route('roles'));
    }
    public function nameExist(Request $request){
        if($request->name){
            if(isset($request->id) && !empty($request->id)){
            $exist = Roles::where([[\DB::raw('lower(name)'),strtolower($request->name)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = Roles::where([[\DB::raw('lower(name)'),strtolower($request->name)],['removed','N']])->get();
            }
            if($exist->count()>0){
                return json_encode('Name is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }
    public function displayNameExist(Request $request){
        if($request->display_name){
            if(isset($request->id) && !empty($request->id)){
            $exist = Roles::where([[\DB::raw('lower(display_name)'),strtolower($request->display_name)],['id','!=',$request->id],['removed','N']])->get();
            }else{
                $exist = Roles::where([[\DB::raw('lower(display_name)'),strtolower($request->display_name)],['removed','N']])->get();
            }
            if($exist->count()>0){
                return json_encode('Display Name is already exist');
            }else{
                return json_encode(true);
            }
        }        
        return json_encode(true);
    }
}
