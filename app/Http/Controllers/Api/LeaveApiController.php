<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ResponseHelper;

use App\Models\Leave;
use App\Models\LeaveType;

class LeaveApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Leave::where('user_id', Auth::guard('api')->user()->id)->orderBy('created_at', 'desc')->get();
            $data->map(function($d){
                // dd($d->caretaker->profile);
                $d->approver_name = $d->approver->profile->name ?? '';
                $d->caretaker_name = $d->caretaker->profile->name ?? '';
                $d->leave_type_name = $d->leave_type->value ?? '';
                $d->images_url = asset('storage/'.$d->attachment);
                return $d;
            });
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function getleavetype()
    {
        try {
            $data = LeaveType::where('status', 'Y')->orderBy('created_at', 'desc')->get();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function change_status(Request $request)
    {
       try {
            // return $request->all();
            $data = Leave::find($request->id)->update($request->except('id'));
            $data = Leave::find($request->id);
            return ResponseHelper::jsonSuccess('success update data',$data );
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // $data = Leave::where('user_id', Auth::guard('api')->user()->id)->orderBy('created_at', 'desc')->get();
            // return ResponseHelper::jsonSuccess('success get data', $data);
            $filename_db = '';
            // return Auth::guard('api')->user()->employee;
            if ($request->hasFile('attachment')) {
                $directory = 'images/leave/';
                $file = $request->file('attachment');
                $fileName = Auth::guard('api')->user()->username.now()->format('His').'.'.$file->getClientOriginalExtension();
                $fileFullPath = 'images/leave/'.$fileName; 
                Storage::disk('public')->put($fileFullPath, file_get_contents($file));
                $filename_db = $fileFullPath;   
            }

            $insert = Leave::insert([
                'user_id'=> Auth::guard('api')->user()->id,
                'caretaker_id'=> $request->caretaker,
                's_date' => $request->s_date,
                'e_date'=>$request->e_date,
                'leave_type_id'=> $request->leave_type_id,
                'tot_day'=> $request->tot_day,
                'attachment'=> $filename_db,
                'note' => $request->note,
                'approver_note' => '',
                'status' => '0',
                'approver_id' => Auth::guard('api')->user()->employee->atasan_id,
                'created_at' => now(),
            ]);
            if ($insert) {
                 return ResponseHelper::jsonSuccess('Berhasil Absen Masuk', $filename_db);
            }else{
                return ResponseHelper::jsonError('error absen', 400);
            }
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function leave_request(String $type)
    {
        try {
            if ($type == 'request') {
                $data= Leave::where('approver_id', Auth::guard('api')->user()->id)->where('status', '0')->orderBy('created_at', 'desc')->get();
            }elseif($type == 'history'){
                $data= Leave::where('approver_id', Auth::guard('api')->user()->id)->where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
            }
            $data->map(function($d){
                // dd($d->caretaker->profile);
                $d->employee_name = $d->user->profile->name ?? '';
                $d->employee_division = $d->user->employee->division->division ?? '';
                $d->employee_position = $d->user->employee->position->position ?? '';
                $d->caretaker_name = $d->caretaker->profile->name ?? '';
                $d->leave_type_name = $d->leave_type->value ?? '';
                $d->images_url = asset('storage/'.$d->attachment);
                return $d;
            });
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
