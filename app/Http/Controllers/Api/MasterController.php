<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Division;
use App\Models\Hazard_location;
use App\Models\Hazard_Report;
use App\Models\Position;
use App\Models\Project;
use App\Models\User;
use App\Models\UserProfileView;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function company()
    {
        try {
            $company = Company::all();
            return ResponseHelper::jsonSuccess('success get data', $company);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }


    public function hazard_location()
    {
        try {
            $company = Hazard_location::all();
            return ResponseHelper::jsonSuccess('success get data', $company);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function division(String $id)
    {
        try {
            $data = Division::where('company_id', $id)->get();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function project(String $id)
    {
        try {
            $data = Project::where('company_id', $id)->get();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function position(String $id)
    {
        try {
            $data = Position::where('company_id', $id)->get();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function user(String $id)
    {
        try {
            $data = User::with('employee', 'profile')->whereHas('employee', function($employee) use($id){
                $employee->where('company_id', $id);
            })->where('status', 'Y')->get();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function pic(Request $request)
    {
        try {
            $name = $request->name ?? 'arrrgghh';
            $data = $data = UserProfileView::where('status', 'Y')->where('name', 'LIKE', '%'.$name.'%')->get();

            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function request_count()
    {
        try {
            $data = Hazard_Report::where('status', 'open')->count();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }
}
