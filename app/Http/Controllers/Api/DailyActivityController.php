<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\DailyActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DailyActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $page = $request->page ?? 1;
            $data = DailyActivity::with([
                'unit_detail',
                'location',
                'created_by.profile',
                'created_by.employee.division',
                'created_by.employee.position',
            ])
                ->where('creator', $user->id)
                ->orderBy('start_time', 'desc')
                ->paginate(6, ['*'], 'page', $page);

            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function serviceIndex(Request $request)
    {
        $validated = $request->validate([
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'start_date' => 'sometimes|date_format:Y-m-d',
            'end_date' => 'sometimes|date_format:Y-m-d|after_or_equal:start_date',
            'creator_id' => 'sometimes|integer|min:1',
            'location_id' => 'sometimes|integer|min:1',
            'status' => 'sometimes|string|max:50',
            'job_type' => 'sometimes|in:UNIT,NON UNIT',
        ]);

        $query = DailyActivity::with([
            'unit_detail',
            'location',
            'created_by.profile',
            'created_by.employee.division',
            'created_by.employee.position',
        ])->orderByDesc('start_time');

        $query->when($validated['start_date'] ?? null, fn ($query, $date) => $query->whereDate('start_time', '>=', $date));
        $query->when($validated['end_date'] ?? null, fn ($query, $date) => $query->whereDate('start_time', '<=', $date));
        $query->when($validated['creator_id'] ?? null, fn ($query, $id) => $query->where('creator', $id));
        $query->when($validated['location_id'] ?? null, fn ($query, $id) => $query->where('id_location', $id));
        $query->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status));
        $query->when($validated['job_type'] ?? null, fn ($query, $jobType) => $query->where('job_type', $jobType));

        $data = $query
            ->paginate($validated['per_page'] ?? 20, ['*'], 'page', $validated['page'] ?? 1)
            ->through(fn (DailyActivity $activity) => [
                'id' => $activity->id,
                'location' => $activity->location ? [
                    'id' => $activity->location->id,
                    'name' => $activity->location->location,
                ] : null,
                'other_location' => $activity->other_location,
                'creator' => $activity->created_by ? [
                    'id' => $activity->created_by->id,
                    'name' => $activity->created_by->profile?->name,
                    'nrp' => $activity->created_by->employee?->nip,
                    'division' => $activity->created_by->employee?->division?->division,
                    'position' => $activity->created_by->employee?->position?->position,
                ] : null,
                'job_type' => $activity->job_type,
                'unit' => $activity->unit_detail,
                'unit_status' => $activity->sts_unit,
                'activity' => $activity->activity,
                'start_time' => $activity->start_time,
                'end_time' => $activity->end_time,
                'duration' => $activity->duration,
                'description' => $activity->desc,
                'status' => $activity->status,
                'created_at' => $activity->created_at,
                'updated_at' => $activity->updated_at,
            ]);

        return ResponseHelper::jsonSuccess('success get data', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();

            $validator = Validator::make($request->all(), [
                'id_location' => 'required',
                'job_type' => 'required|in:NON UNIT,UNIT',
                'sts_unit' => 'required_if:job_type,UNIT',
                'id_unit' => 'required_if:job_type,UNIT',
                'unit_code' => 'required_if:job_type,UNIT',
                'activity' => 'required',
                'desc' => 'required',
                'end_time' => 'required',
                'duration' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }

            $id_unit = null;
            DB::beginTransaction();
            if ($request->job_type == 'UNIT') {
                DB::table('daily_activity_unit_details')->insert([
                    'id_unit' => $request->id_unit,
                    'unit_code' => $request->unit_code,
                    'plate_number' => $request->plate_number,
                    'unit_type_id' => $request->unit_type_id,
                    'unit_type' => $request->unit_type,
                    'unit_category' => $request->unit_category,
                    'unit_cat_code' => $request->unit_cat_code,
                    'unit_model_id' => $request->unit_model_id,
                    'brand' => $request->brand,
                    'model' => $request->model,
                ]);

                $id_unit = DB::getPdo()->lastInsertId();
            }

            DB::table('daily_activity')->insert([
                'id_location' => $request->id_location,
                'other_location' => $request->other_location,
                'creator' => $user->id,
                'job_type' => $request->job_type,
                'unit' => $id_unit,
                'sts_unit' => $request->sts_unit,
                'activity' => $request->activity,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'duration' => $request->duration,
                'desc' => $request->desc,
                'status' => 'ACTIVE',
            ]);
            DB::commit();

            return ResponseHelper::jsonSuccess('Berhasil');
        } catch (\Throwable $err) {
            DB::rollBack();

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
