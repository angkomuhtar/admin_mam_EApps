<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Division;
use App\Models\Employee;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $data =  User::all();
        $departemen = Division::all();

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
            return DataTables::eloquent($data)->toJson();
        }

        return view('pages.dashboard.master.users', [
            'pageTitle' => 'Users',
            'tableData' => $data,
            'dept'=>$departemen
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
}
