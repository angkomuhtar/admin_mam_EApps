<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Division;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\ClockLocation;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $departemen = Division::all();
        $location = ClockLocation::where('status', 'Y')->get();

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
            'dept'=>$departemen,
            'loc'=>$location
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

    public function permission(Request $request)
    {
        $departemen = Division::all();
        $location = ClockLocation::where('status', 'Y')->get();

        if ($request->ajax()) {
            $data = User::with('employee', 'employee.division', 'employee.position', 'profile', 'smartwatch', 'roles', 'permissions')
            ->whereHas('profile', function($query) use($request) {
                    $query->where('name','LIKE','%'.$request->name.'%');
            });
            if ($request->division) {
                $data->whereHas('employee', function($query) use($request) {
                    $query->where('division_id', $request->division);
                });
            }
            return DataTables::of($data->get())->toJson();
        }

        return view('pages.dashboard.master.users-permission', [   
            'pageTitle' => 'Users Permission',
            'dept'=>$departemen,
            'loc' => $location
        ]);
    }

    public function permission_edit(Request $request, String $id){
       $role = Role::all();
       $permission = Permission::all();
       $users = User::with('profile', 'employee', 'roles', 'permissions')->where('id', $id)->first();

       $users->roles_array = $users->roles->pluck('name')->toArray();
       $users->permissions_array = $users->permissions->pluck('name')->toArray();

      return view('pages.dashboard.master.permission_edit', [
        'pageTitle' => 'Tambah Karyawan',
        'role'=> $role,
        'permission'=> $permission,
        'user'=> $users
      ]);
    }

    public function permission_update(Request $request, String $id){
        $users = User::findOrFail($id);
        $roles = $request->input('arrayRole');
        $roles = is_array($roles) ? $roles : [$roles];
        $users->syncRoles($roles);
        $permissions = $request->input('arrayPermission');
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        $users->syncPermissions($permissions);
        return back()->with('success', "Role has been assigned to user {$users->profile->name}");
     }
}
