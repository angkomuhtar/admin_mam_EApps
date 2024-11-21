<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    //
    public function index(Request $request)
    {
        $users = Auth::guard('web')->user();
        $data = Company::all();

        if ($request->ajax()) {
            return DataTables::of($data)->toJson();
        }
        return view('pages.dashboard.master.company', [
            'pageTitle' => 'Users',
        ]);
    }
    public function store(Request $request)
    {   

        $validator = Validator::make($request->all(), [
            'company'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $data = Company::create([
            'company' => $request->company,
        ]);

        return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Disimpan'
            ]);

    }

    public function edit($id)
    {
        $data = Company::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Data Divisi Berhasil Disimpan',
            'data' => $data
        ]);
    }


    public function update(Request $request, $id)
    {
        $update = Company::find($id);
        $update->company = $request->company;
        if($update->save()){
            return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Update',
                'data' => $update
            ]);
        }
    }

    public function destroy($id) 
    {
        $delete = Company::destroy($id);
        if ($delete){
            return response()->json([
                'success' => true,
                'message' => 'Data divisi berhasil disimpan'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "error saat menghapus data"
            ]);
        }
    }


    public function index_proj(Request $request)
    {
        $user = Auth::guard('web')->user();
        $data = $user->user_roles == 'ALL' ? Company::all() : Company::where('id', $user->employee->company_id)->get();
        $proj = Project::with('company')->whereHas('company', function($query) use($user){
            if ($user->user_roles != 'ALL') {
                $query->where('id', $user->employee->company_id);
            }
        })->get();

        if ($request->ajax()) {
            return DataTables::of($proj)->toJson();
        }
        return view('pages.dashboard.master.project', [
            'pageTitle' => 'Users',
            'company'=> $data
        ]);
    }
    public function store_proj(Request $request)
    {   

        $validator = Validator::make($request->all(), [
            'company'     => 'required',
            'project'     => 'required',
            'code'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $data = Project::create([
            'company_id' => $request->company,
            'code' => $request->code,
            'name' => $request->project,
        ]);

        return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Disimpan'
            ]);

    }

    public function edit_proj($id)
    {
        $data = Project::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Data Divisi Berhasil Disimpan',
            'data' => $data
        ]);
    }


    public function update_proj(Request $request, $id)
    {
        $update = Project::find($id);
        $update->company_id = $request->company;
        $update->code = $request->code;
        $update->name = $request->project;
        if($update->save()){
            return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Update',
                'data' => $update
            ]);
        }
    }

    public function destroy_proj($id) 
    {
        $delete = Project::destroy($id);
        if ($delete){
            return response()->json([
                'success' => true,
                'message' => 'Data divisi berhasil disimpan'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "error saat menghapus data"
            ]);
        }
    }


    
}
