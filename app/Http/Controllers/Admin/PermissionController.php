<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $data = Permission::all();
            return DataTables::of($data)->toJson();
        }
        return view('pages.dashboard.master.permission');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'     => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        Permission::create(['name'=> $request->name, 'guard_name'=> 'web']);
        
        return response()->json([
            'success' => true,
            'message' => 'Data Permission Berhasil di tambah'
        ]);

    }

    public function edit($id)
    {
        $data = Permission::find($id);
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
        $update = Permission::find($id);
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
        $delete = Permission::destroy($id);
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
}
