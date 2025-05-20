<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\InspectionCard;
use App\Models\SubInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        
    }

    public function index()
    {
        $data = Inspection::where('status', 'Y')->get();

        if ($data) {
            return ResponseHelper::jsonSuccess('Berhasil', $data);
        }else{
            return ResponseHelper::jsonError('error', 400);
        }
    }

    public function store(Request $request)
    {
        try{
            $user =  Auth::guard('api')->user();

            $validator = Validator::make($request->all(), [
                'id_location' => 'required',
                'detail_location' => 'required',
                'date' => 'required',
                'recommendation' => 'required',
                'inspection_id' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }

            if ($user->employee->position->position_class->class) {
                return ResponseHelper::jsonError('anda tidak memiliki akses untuk melakukan ini', 403);
            }


            $answer= $request->except(['id_location', 'other_location', 'detail_location', 'date', 'recommendation', 'inspection_id', 'shift']);

            $filteredanswer = array_keys(collect($answer)->reject(function ($value, $key) {
                return str_starts_with($key, 'note_') || str_starts_with($key, 'date_');
            })->toArray());

            // return ResponseHelper::jsonSuccess('Berhasil', $filteredanswer);

            
            $acronim = 'INS/'.str_pad($request->id_location, 2, '0', STR_PAD_LEFT).'/';
            $lastReport = InspectionCard::where('inspection_number', 'LIKE', $acronim.'%')->orderBy('id', 'desc')->first();
            $lastNumber = $lastReport ? intval(substr($lastReport->inspection_number, -4)) : 0;
            $itemNumber = $acronim.date('Y').'-'.date('m').'/' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            // return ResponseHelper::jsonError($itemNumber, 500);
            DB::beginTransaction();
            DB::table('inspection_card')->insert([
                'created_by' =>$user->id,
                'departement_id' => $user->employee->division_id,
                'location_id' => $request->id_location,
                'inspection_number' => $itemNumber,
                'shift' => $request->shift,
                'other_location' => $request->other_location,
                'detail_location' => $request->detail_location,
                'inspection_date' => $request->date,
                'recomended_action' => $request->recommendation,
                'inspection_id' => $request->inspection_id,
            ]);
            $id = DB::getPdo()->lastInsertId();

            foreach ($filteredanswer as $key => $value) {
                DB::table('inspection_answer')->insert([
                    'inspection_card_id' => $id,
                    'question_slug' => $value,
                    'answer' => $request[$value],
                    'note' => $request['note_'.$value] ?? null,
                    'due_date' => $request['date_'.$value] ?? null
                ]);
            }
            DB::commit();

            return ResponseHelper::jsonSuccess('Berhasil', $id);
        } catch (\Exception $err) {
            DB::rollBack();
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }


    public function getQuestion(String $slug)
    {
        try{
            $data = SubInspection::with('inspection','question')->whereHas(
                'inspection', function($query) use ($slug){
                    $query->where('slug', $slug);
                }
            )->get();
            if ($data) {
                return ResponseHelper::jsonSuccess('Berhasil', $data);
            }else{
                return ResponseHelper::jsonError('error', 400);
            }
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function history(Request $request)
    {
        $page = $request->page ?? 1;
        $status = $request->status ?? '';
        $user =  Auth::guard('api')->user();
        $data  = InspectionCard::with('location', 'inspection')->where('created_by', $user->id)
        ->where('status', 'LIKE', '%'.$status.'%')
        ->orderBy('created_at', 'desc')
        ->paginate(15, ['*'], 'page', $page);
        
        if ($data){
            return ResponseHelper::jsonSuccess('Berhasil', $data);
        }else{
            return ResponseHelper::jsonError('Error', 400);
        }
    }

    public function list_report(Request $request)
    {
        $page = $request->page ?? 1;
        $status = $request->status ?? '';
            $search = $request->search ?? '';
        $user =  Auth::guard('api')->user();
        $data  = InspectionCard::with('location', 'inspection','creator', 'supervisor')
        ->where('status', 'LIKE', '%'.$status.'%')
        ->whereHas('creator', function($q) use($search){
            $q->where('name', 'like', '%'.$search.'%');
        })
        ->ByDept()
        ->orderBy('created_at', 'desc')
        ->paginate(15, ['*'], 'page', $page);
        
        if ($data){
            return ResponseHelper::jsonSuccess('Berhasil', $data);
        }else{
            return ResponseHelper::jsonError('Error', 400);
        }
    }

    public function show(string $id)
    {
        try {
            $data = InspectionCard::with('location', 'inspection', 'answer', 'answer.question.sub_inspection', 'creator', 'supervisor')->find($id);
            if ($data) {
                return ResponseHelper::jsonSuccess('Berhasil', $data);
            }else{
                return ResponseHelper::jsonError('error', 400);
            }
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }
 
    public function count()
    {
        try {
            $user =  Auth::guard('api')->user();
            $data = InspectionCard::where('status', 'created')->ByDept()->count();
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function verified(string $id)
    {
        try {
            $user =  Auth::guard('api')->user();
            $data = InspectionCard::find($id);
            if ($data) {
                $data->status = 'verified';
                $data->supervised_by = $user->id;
                $data->save();
                return ResponseHelper::jsonSuccess('success get data', $data);
            }else{
                return ResponseHelper::jsonError('error', 404);
            }
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }
}
