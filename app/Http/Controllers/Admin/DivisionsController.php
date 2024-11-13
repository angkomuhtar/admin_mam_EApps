<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DivisionsController extends Controller
{

    public function index(Request $request)
    {
        $company = Company::all();
        $users = Auth::guard('web')->user();

        if ($request->ajax()) {
            $data = Division::with('company')->where('company_id', $users->employee->company_id)->select('divisions.*');
            return DataTables::eloquent($data)->toJson();
        }
        return view('pages.dashboard.master.division', [
            'pageTitle' => 'Users',
            'company' => $company
        ]);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {   

        $validator = Validator::make($request->all(), [
            'division'     => 'required',
            'company'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $data = Division::create([
            'company_id' => $request->company,
            'division' => $request->division
        ]);

        return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Disimpan'
            ]);

    }

    public function destroy($id) 
    {
        $delete = Division::destroy($id);
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
        $data = Division::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Data Divisi Berhasil Disimpan',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $update = Division::find($id);
        $update->division = $request->division;
        if($update->save()){
            return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Update',
                'data' => $update
            ]);
        }
    }
}
