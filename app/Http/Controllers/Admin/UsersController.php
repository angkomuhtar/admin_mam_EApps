<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Division;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\ClockLocation;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $data =  User::all();
        $departemen = Division::all();
        $locaation = ClockLocation::where('status', 'Y')->get();

        if ($request->ajax()) {
            $data = User::with('employee', 'employee.division', 'employee.position', 'profile', 'smartwatch')
            ->whereHas('profile', function($query) use($request) {
                    $query->where('name','LIKE','%'.$request->name.'%');
            });
            if ($request->division) {
                $data->whereHas('employee', function($query) use($request) {
                    $query->where('division_id', $request->division);
                });
            }
            return DataTables::of($data)
                ->addColumn('lokasi', function($model){
                    return $model->employee ? json_decode($model->employee->lokasi) : null;
                })->make(true);
            // return [0]->employee->lokasi;
            // return DataTables::eloquent($data)->toJson();
        }

        return view('pages.dashboard.master.users', [   
            'pageTitle' => 'Users',
            'tableData' => $data,
            'dept'=>$departemen,
            'loc'=>$locaation
        ]);
    }

    public function status(Request $request, $id)
    {
        $user = User::find($id);
        
        $user->update([
            'status' => 'N',
        ]);
        $employee = Employee::where('user_id', $id)->update([
            // 'division_id' => 11   
            'end_date'=> $request->tgl, 
            'contract_status' => $request->type     
        ]);
        if ($user) {
        return response()->json([
            'success' => true,
            'data' => 'Data Created'
        ]);
        }else{
        return response()->json([
            'success' => false,
            'msg' => 'Errorki tolo'
        ]);
        }
    }
    
    public function reset_phone($id)
    {
        $user = User::find($id)->update([
            'phone_id' => NULL,
          ]);
          if ($user) {
            return response()->json([
                'success' => true,
                'data' => 'Data Created'
            ]);
          }else{
            return response()->json([
              'success' => false,
              'msg' => 'Errorki tolo'
            ]);
          }
    }

    public function create()
    {
          return view('pages.dashboard.users.create', [
            'pageTitle' => 'Tambah User'
        ]);
    }

    public function update_location(Request $request, String $id)
    {
        $user = Employee::where('user_id', $id)->first();
        $location = '';
        foreach ($request->arrayLocation as $key => $value) {
            if (count($request->arrayLocation) - 1 > $key) {
                $location = $location.''.$value.',';
            }else{
                $location = $location.''.$value;
            }
        }

        $user->absen_location = $location;
        if ($user->save()) {
            return response()->json([
                'success' => true,
                'data' => 'Data Created'
            ]);
          }else{
            return response()->json([
              'success' => false,
              'msg' => 'Errorki tolo'
            ]);
          }
    }
}
