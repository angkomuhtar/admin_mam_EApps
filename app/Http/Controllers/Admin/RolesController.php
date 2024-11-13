<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    //
    public function index(Request $request)
    {
        
        $permission = Permission::all();
        if ($request->ajax()) {
            $data = Role::with('permissions')->get();
            return DataTables::of($data)->toJson();
        }
        return view('pages.dashboard.master.roles',[
            'permission'=> $permission
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'role'     => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        Role::create(['name'=> $request->role, 'guard_name' => 'web']);
        
        return response()->json([
            'success' => true,
            'message' => 'Data Roles Berhasil di tambah'
        ]);

    }

    public function edit($id)
    {
        $data = Role::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Data Divisi Berhasil Disimpan',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $update = Role::find($id);
        $update->name = $request->role;
        if($update->save()){
            return response()->json([
                'success' => true,
                'message' => 'Data Role Berhasil Update',
                'data' => $update
            ]);
        }
    }

    public function destroy($id) 
    {
        $delete = Role::destroy($id);
        if ($delete){
            return response()->json([
                'success' => true,
                'message' => 'Data Role berhasil disimpan'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "error saat menghapus data"
            ]);
        }
    }

    public function get_permission(String $id){
        $data = Role::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $data->permissions
        ]);
    }

    public function set_permission(Request $request, String $id){
        $role = Role::findOrFail($id);
        $permission = $request->input('arrayPermission');
        $role->syncPermissions($permission);
        return response()->json([
            'success' => true,
            'message' => 'Data Role Berhasil Update'
        ]);
    }



}
