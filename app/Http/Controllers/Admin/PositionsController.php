<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class PositionsController extends Controller
{
    public function index(Request $request)
    {
        $users = Auth::guard('web')->user();
        if ($request->ajax()) {
            $data = Position::with('division','company')->whereHas('company',function($query) use($users){
                if ($users->user_roles != 'ALL') {
                    $query->where('id', $users->employee->company_id); 
                }
            });
            return DataTables::eloquent($data)->toJson();
        }

        $division = Division::all();
        $company =  $users->user_roles == 'ALL' ? Company::all() : Company::where('id', $users->employee->company_id)->get();
        return view('pages.dashboard.master.position', [
            'division' => $division,
            'company'=> $company
        ]);
    }
    
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'company'     => 'required',
            'division'     => 'required',
            'position'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $data = Position::create([
            'company_id' => $request->company,
            'division_id' => $request->division,
            'position' => $request->position,
        ]);

        return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Disimpan'
            ]);

    }

    public function destroy($id) 
    {
        $delete = Position::destroy($id);
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

    public function edit($id)
    {
        $data = Position::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Data Divisi Berhasil Disimpan',
            'data' => $data
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company'     => 'required',
            'division'     => 'required',
            'position'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $update = Position::find($id);
        $update->division_id = $request->division;
        $update->position = $request->position;
        if($update->save()){
            return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Update',
                'data' => $update
            ]);
        }
    }
}
