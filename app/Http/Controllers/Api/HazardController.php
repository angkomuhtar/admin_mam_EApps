<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Hazard_action;
use App\Models\Hazard_Report;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HazardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user =  Auth::guard('api')->user();
            $page = $request->page ?? 1;
            $status = $request->status ?? '';
            $data = Hazard_Report::with([
                'location', 
                'company', 
                'project', 
                'division', 
                ])
            ->where('status','like', '%'.$status.'%')
            ->where('created_by', $user->id)
            ->orderBy('date_time', 'desc')
            ->paginate(6, ['*'], 'page', $page);
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function list(Request $request)
    {
        try {
            $user =  Auth::guard('api')->user();
            $page = $request->page ?? 1;
            $status = $request->status ?? '';
            $search = $request->search ?? '';
            $data = Hazard_Report::with([
                'location', 
                'company', 
                'project', 
                'division', 
                'createdBy', 
                'createdBy.profile', 
                'createdBy.employee.division', 
                'hazardAction', 
                'hazardAction.pic', 
                'hazardAction.pic.profile'])
            ->where('status','like', '%'.$status.'%')
            ->whereHas('createdBy.profile', function($q) use ($search){
                $q->where('name', 'like', '%'.$search.'%');
            })
            ->byDept()
            ->orderBy('date_time', 'desc')
            ->paginate(15, ['*'], 'page', $page);
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function count_report(){
        try {
            $user =  Auth::guard('api')->user();
            $data = Hazard_Report::where('status','like', '%OPEN%')
            ->byDept()->count();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function list_pekerjaan(Request $request)
    {
        try {
            $user =  Auth::guard('api')->user();
            $page = $request->page ?? 1;
            $status = $request->status ?? '';
            $data = Hazard_Report::with([
                'location', 
                'company', 
                'project', 
                'division', 
                'createdBy', 
                'createdBy.profile', 
                'createdBy.employee.division', 
                'hazardAction', 
                'hazardAction.pic', 
                'hazardAction.pic.profile'
                ])
            ->whereHas('hazardAction', function($q) use ($user){
                $q->where('pic', $user->id);
            })
            ->where('status','like', '%'.$status.'%')
            ->orderBy('date_time', 'desc')
            ->paginate(6, ['*'], 'page', $page);
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
           $data = Hazard_Report::with([
                'location', 
                'company', 
                'project', 
                'division', 
                'createdBy', 'createdBy.profile', 'createdBy.employee.division', 
                'hazardAction', 'hazardAction.pic', 'hazardAction.pic.profile', 'hazardAction.pic.employee.position',
                'hazardAction', 'hazardAction.supervisedBy', 'hazardAction.supervisedBy.profile', 'hazardAction.supervisedBy.employee.position',
                ])->where('id', $id)->first();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.xpq
     */
    public function store(Request $request)
    {
        try {
            $user =  Auth::guard('api')->user();
            if ($user->employee->position->position_class->class < 3) {
                return ResponseHelper::jsonError('Tidak Boleh', 422);
            }
            $validator = Validator::make($request->all(), [
                'id_location' => 'required',
                'detail_location' => 'required',
                'company_id' => 'required',
                'project_id' => 'required',
                'dept_id' => 'required',
                'category' => 'required',
                'reported_condition' => 'required',
                'recomended_action' => 'required',
                'action_taken' => 'required',
                'report_attachment' => 'required',
                'due_date' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }
            if (!$request->hasFile('report_attachment')) {
                return ResponseHelper::jsonError('file upload Not found', 422);
            }
            $div = Division::find($request->dept_id);
            $acronim = 'HR-'.$div->acronim;
            $lastReport = Hazard_Report::where('hazard_report_number', 'LIKE', $acronim.'%')->orderBy('id', 'desc')->first();
            $lastNumber = $lastReport ? intval(substr($lastReport->hazard_report_number, -6)) : 0;
            $hazard_report_number = $acronim . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            $file = $request->file('report_attachment');
            $fileName = $hazard_report_number.now()->format('His');
            $url = cloudinary()->upload($file->getRealPath(), [
                "public_id" => $fileName,
                "folder"    => "hazard_report",
                'transformation' => [
                    'quality' => "auto",
                    'fetch_format' => "auto"
                    ]
                    ])->getSecurePath();

            $today = Carbon::now()->setTimeZone('Asia/Makassar')->format('Y-m-d H:i:s');
            $insert = Hazard_Report::insert([
                'date_time' => $today,
                'hazard_report_number' => $hazard_report_number,
                'id_location' => $request->id_location,
                'other_location' => $request->other_location,
                'detail_location' => $request->detail_location,
                'company_id' => $request->company_id,
                'project_id' => $request->project_id,
                'dept_id' => $request->dept_id,
                'category' => $request->category,
                'reported_condition' => $request->reported_condition,
                'recomended_action' => $request->recomended_action,
                'action_taken' => $request->action_taken,
                'due_date' => $request->due_date,
                'report_attachment' => $url,
                'created_by'=> Auth::guard('api')->user()->id,
                'status' => 'OPEN',
                'created_at' => now(),
             ]);

            if ($insert) {
                return ResponseHelper::jsonSuccess('Berhasil', $insert);
            }else{
                return ResponseHelper::jsonError('error', 400);
            }
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function set_pic(Request $request, string $id)
    {
        try {
            $store = Hazard_action::create([
                'hazard_id' => $id,
                'pic' => $request->pic,
                'status' => 'WORKING',
                'notes' => $request->notes,
                'attachment' => $request->attachment,
                'supervised_by' => Auth::guard('api')->user()->id
            ]);
            $update = Hazard_Report::where('id', $id)->update([
                'status' => 'ONPROGRESS'
            ]);
            if ($update) {
                return ResponseHelper::jsonSuccess('Berhasil', $update);
            }else{
                return ResponseHelper::jsonError('error', 400);
            }
        } catch (\Throwable $th) {
            return ResponseHelper::jsonError($th->getMessage(), 500);
        }
    }

    public function update_action(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_action' => 'required',
                'action_attachment' => 'required',
                'action_status' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }
            if (!$request->hasFile('action_attachment')) {
                return ResponseHelper::jsonError('file upload Not found', 422);
            }

            $hazard_action = Hazard_action::find($request->id_action);
            // return ResponseHelper::jsonError( $request->id_action, 500);
            $hazard_report_number = $hazard_action->hazard->hazard_report_number;
            $file = $request->file('action_attachment');
            $fileName = $hazard_report_number.now()->format('His');
            $url = cloudinary()->upload($file->getRealPath(), [
                "public_id" => $fileName,
                "folder"    => "hazard_action",
                'transformation' => [
                    'quality' => "auto",
                    'fetch_format' => "auto"
                    ]
                    ])->getSecurePath();

            $update = $hazard_action->update([
                'attachment' => $url,
                'status' => $request->action_status,
                'notes' => $request->action_note
            ]);
            $update_report = $hazard_action->hazard->update([
                'status' => $request->action_status == 'DONE' ? 'CLOSED' : 'ONPROGRESS'
            ]);


            if ($update_report) {
                return ResponseHelper::jsonSuccess('Berhasil', $update_report);
            }else{
                return ResponseHelper::jsonError('error', 400);
            }
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
