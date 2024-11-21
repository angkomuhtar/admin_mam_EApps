<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Shift;
use App\Models\Company;
use App\Models\Options;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Position;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        $category= Options::select('kode', 'value')->where('type', 'category')->get();
        $shift= WorkSchedule::select('code', 'name')->orderBy('name')->get();

        if ($user->user_roles == 'ALL') {
          $dept = Division::where('id', '<>', '');
          $project = Project::where('id', '<>', '');
        }else{
          $dept = Division::where('company_id', $user->employee->company_id);
          $project = Project::where('company_id', $user->employee->company_id);
          if ($user->user_roles == 'PROJ') {
            $project->where('id', $user->employee->project_id);
          } elseif($user->user_roles != 'COMP' && $user->user_roles != 'ALL') {
            $project->where('id', $user->employee->project_id);
            $dept->where('id', $user->employee->division_id);
          }
        }

        if ($request->ajax()) {
          $data = User::with('employee','profile','employee.project', 'employee.division', 'employee.position', 'employee.category', 'employee.work_schedule')
            ->whereHas('profile', function ($query) use ($request){
              $query->where('name', 'LIKE', '%'.$request->name.'%');
            })
            ->whereHas('employee', function($q) use($request){
              $q->ofLevel();
              if ($request->division != null || $request->departement != '') {
                $q->where('division_id', $request->division);
              }

              if ($request->nrp != null) {
                $q->where('nip', '');
              }

              if ($request->project != null) {
               $q->where('project_id', $request->project); 
              }
            });


          $dt = DataTables::of($data->get())
          ->addColumn('category', function ($row) use($category) {
            return $category->toArray();
          })
          ->addColumn('shift', function ($row) use($shift) {
            return $shift->toArray();
          });
          return $dt->make(true);
        }

        return view('pages.dashboard.employee.index', [
            'pageTitle' => 'Data Karyawan',
            'category' => $category,
            'shift' => $shift,
            'departement' => $dept->get(),
            'project' => $project->get()
        ]);
    }

    public function create()
    {

      $user = Auth::guard('web')->user();
      $education =  Options::where("type","education")->get();
      $religion =  Options::where("type","religion")->get();
      $marriage =  Options::where("type","marriage")->get();
      $company =  $user->user_roles == 'ALL' ? Company::all() : Company::where('id', $user->employee->company_id)->get();
      $workhours =  WorkSchedule::all();
      $project =  $user->user_roles == 'ALL' ? Project::all() : Project::where('company_id', $user->employee->company_id)->get();

      return view('pages.dashboard.employee.create', [
        'pageTitle' => 'Tambah Karyawan',
        'education'=> $education,
        'religion'=> $religion,
        'marriage'=> $marriage,
        'project'=> $project,
        'workhours'=> $workhours,
        'company'=> $company,
    ]);
    }

    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'company_id'     => 'required',
        'division_id'     => 'required',
        'position_id'  => 'required',
        'nip'  => 'required',
        'doh'  => 'required|date',
        'wh_code'  => 'required',
        'project_id'  => 'required',
        'doh'  => 'required|date',
        'status'  => 'required',
      ],[
          'required' => 'tidak boleh kosong',
          'date' => 'Harus tanggal dengan format YYYY/MM/DD'
      ]);
      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'data' => $validator->errors()
          ]);
      }
      DB::beginTransaction();
      try {
        $users = User::create([
          'username' => $request->username, 
          'email' => $request->email,
          'email_verified_at' => now(),
          'password' => bcrypt($request->password)
        ]);

        $profile = $users->profile()->create($request->only([
          'name', 
          'card_id', 
          'kk', 
          'education', 
          'tmp_lahir', 
          'tgl_lahir',
          'gender',
          'religion',
          'marriage',
          'id_addr',
          'live_addr',
          'phone'
        ]));

        $employee = $users->employee()->create([
          'company_id' => $request->company_id,
          'division_id' => $request->division_id,
          'position_id' => $request->position_id,
          'wh_code' => $request->wh_code,
          'nip' => $request->nip,
          'project_id' => $request->project_id,
          'doh' => $request->doh,
          'status' => $request->status,
        ]);
        DB::commit();
      } catch (Exception $th) {
        DB::rollBack();
        return response()->json([
          'success' => false,
          'type' => 'err',
          'data' => $th
      ]);
      }

      
      return response()->json([
          'success' => true,
          'data' => 'Data Created'
      ]);
    }

    public function show(string $id)
    {
        //
    }
    
    public function edit_profile(string $id)
    {
      $education =  Options::where("type","education")->get();
      $religion =  Options::where("type","religion")->get();
      $marriage =  Options::where("type","marriage")->get();

      $profile = Profile::where("user_id", $id)->first();

      // dd($profile);
      return view('pages.dashboard.employee.profile_edit', [
        'pageTitle' => 'Tambah Karyawan',
        'education'=> $education,
        'religion'=> $religion,
        'marriage'=> $marriage,
        'profile' => $profile
      ]);
    }
    
    public function update_profile(Request $request, string $id)
    {
      $validator = Validator::make($request->all(), [
        'name'=> 'required',
        "phone"=> 'required',
        "gender"=> 'required',
        "card_id"=> 'required|min:14|numeric',
        "kk"=> 'required',
        "tmp_lahir"=> 'required',
        "tgl_lahir"=> 'required|date',
        "education"=> 'required',
        "religion"=> 'required',
        "marriage"=> 'required',
        "id_addr"=> 'required',
        "live_addr"=> 'required',

      ], [
          'required' => 'tidak boleh kosong',
          'min' => 'minimal :min karakter',
          'numeric' => 'hanya boleh angka',
          'tgl_lahir.date' => 'Harus tanggal dengan format YYYY/MM/DD'
      ]);
      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'data' => $validator->errors()
          ]);
      }

      $update = Profile::find($id)->update($request->all());

      if($update){
        return response()->json([
          'success' => true,
          'msg'
      ]);
      }
    }

    public function edit_employee(string $id)
    {

      $project = Project::all();
      $whcode = WorkSchedule::all();
      $departement = Division::all();
      $employee = Employee::where("user_id", $id)->first();
      $category = Options::where("type", "category")->get();
      $position = Position::where("division_id", $employee->division_id)->get();

      // dd($profile);
      return view('pages.dashboard.employee.employee_edit', [
        'pageTitle' => 'Tambah Karyawan',
        'employee' => $employee,
        'project' => $project,
        'whcode' => $whcode,
        'departement' => $departement,
        'position' => $position,
        'category' => $category,
      ]);
    }
    
    public function update_employee(Request $request, string $id)
    {
      $validator = Validator::make($request->all(), [
        'company_id'     => 'required',
        'division_id'     => 'required',
        'position_id'  => 'required',
        'doh'  => 'required|date',
        'wh_code'  => 'required',
        'project_id'  => 'required',
        'doh'  => 'required|date',
        'nip'  => 'required',
        'status'  => 'required',
      ],[
          'required' => 'tidak boleh kosong',
          'date' => 'Harus tanggal dengan format YYYY/MM/DD'
      ]);
      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'data' => $validator->errors()
          ]);
      }

      $update = Employee::find($id)->update($request->all());
      if($update){
        return response()->json([
          'success' => true,
          'msg' => 'success'
      ]);
      }
    }
    
    public function destroy()
    {
      return 'tre';
    }

    public function pass_reset($id)
    {
      $user = User::find($id)->update([
        'password' => bcrypt('mam123'),
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

    public function update_category($id, Request $request)
    {
      $user = Employee::find($id)->update([
        'category_id' => $request->value,
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

    public function update_shift($id, Request $request)
    {
      $user = Employee::find($id)->update([
        'wh_code' => $request->value,
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
}
