<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\ViewClockSleep;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Hazard_Report;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class AjaxController extends Controller
{
    public function getDivision(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Division::where('company_id', $id)->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function getPosition(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Position::where('division_id', $id)->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function userValidate(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'username'     => 'required|string|unique:users',
                'email'     => 'required|email:rfc|string|unique:users',
                'password'  => 'required|min:6|confirmed',
                'password_confirmation'  => 'min:6|required',
            ], [
                'required' => 'tidak boleh kosong',
                'min' => 'minimal :min karakter',
                'confirmed' => 'password harus sama dengan confirm password'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'data' => $validator->errors()
                ]);
            }
            $data = Division::where('company_id', 1)->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function profileValidate(Request $request)
    {
        if ($request->ajax()) {
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
            return response()->json([
                'success' => true,
                'data' => 'success'
            ]);
        }
    }

    public function ajaxBarchart (Request $request){
        $date = $request->date;
        // $endweek = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
        // $startweek = Carbon::createFromFormat('Y-m-d', $date)->subDays(6)->format('Y-m-d');

        // return $request->shift;
        // $dataSleepWeek = ViewClockSleep::where('shift','like','%'.$request->shift.'%')
        //     ->whereNotNull('watch_dist')
        //     // ->whereBetween('sleep_date',[$startweek, $endweek]);
        //     ->where('sleep_date',$startweek);

        // // $dataSleepWeek->get()->filter()->groupBy('date');
        // return $dataSleepWeek->get();

        $endDate = Carbon::parse($date);
        $startDate = Carbon::parse($date)->subDays(6);
        $data = array();
        $enough = array();
        $bad = array();
        $legend = array();
        $no = 0;


        $dataSleep = DB::table('v_clock_sleep')
                 ->select('sleep_cat', DB::raw('count(*) as total'))
                 ->where('shift','like','%'.$request->shift.'%')
                 ->whereNotNull('watch_dist')
                 ->where('sleep_date',$startDate)
                 ->groupBy('sleep_cat')
                 ->get();

        // return $dataSleep;
        while ($startDate->lte($endDate)){
            $dataSleep = DB::table('v_clock_sleep')
                ->select('sleep_cat', DB::raw('count(*) as total'))
                ->where('shift','like','%'.$request->shift.'%')
                ->whereNotNull('watch_dist')
                ->where('sleep_date',$startDate)
                ->groupBy('sleep_cat')
                ->get();
            $fit[$no] = 0;
            $enough[$no] = 0;
            $bad[$no] = 0;

            foreach ($dataSleep as $ds) {
                if ($ds->sleep_cat == 'BAD') {
                    $bad[$no] = $ds->total;
                }elseif ($ds->sleep_cat == 'ENOUGH') {
                    $enough[$no] = $ds->total;
                }elseif ($ds->sleep_cat == 'FIT') {
                    $fit[$no] = $ds->total;
                }
            }


            // if (isset($dataSleepWeek[$startDate->format('Y-m-d')])) {
            //     $total = $dataSleepWeek[$startDate->format('Y-m-d')]->filter()->groupBy('sleep_cat');
            //     $fit[$no] = isset($total['FIT'])? $total['FIT']->count() : 0;
            //     $enough[$no] = isset($total['ENOUGH'])? $total['ENOUGH']->count() : 0;
            //     $bad[$no] = isset($total['BAD'])? $total['BAD']->count() : 0;
            // }else{
            //     $fit[$no] = 0;
            //     $enough[$no] = 0;
            //     $bad[$no] = 0;
            // }
            $legend[$no] = $startDate->format('d M');
            $startDate->addDay();
            $no++;
        }

        return response()->json([
            "series" => [
                ['name' => 'fit to works', 'data' => $fit],
                ['name' => 'dalam pengawasan', 'data' => $enough],
                ['name' => 'istirahat', 'data' => $bad],
           ],
            "legend" => $legend
        ]);

        // foreach ($dataSleepWeek as $key => $item) {
        //     return $key;
        // }


    }

    public function getHazardYearly(Request $request)
    {
        $year = $request->year;

        $data = Hazard_Report::query()
            ->where('company_id', 1)
            ->whereIn('project_id', [1, 4, 6])
            ->whereYear('created_at', $year)
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json([
            "categories" => ["Open", "On Progress", "Closed"],
            "series" => [
                [
                    "name" => "Hazard Reports",
                    "data" => [
                        $data['OPEN'] ?? 0,
                        $data['ONPROGRESS'] ?? 0,
                        $data['CLOSED'] ?? 0
                    ]
                ],
            ],
        ]);
    }

    public function getHazardByCategory(Request $request)
    {
        $year = $request->year;

        $data = Hazard_Report::query()
            ->where('company_id', 1)
            ->whereIn('project_id', [1, 4, 6])
            ->whereYear('created_at', $year)
            ->selectRaw("
                CASE
                    WHEN category = 'KTA' THEN 'Faktor Kondisi Tidak Aman'
                    WHEN category = 'TTA' THEN 'Faktor Tindakan Tidak Aman'
                    ELSE 'Lainnya'
                END as category,
                COUNT(*) as count
            ")
            ->groupBy('category')
            ->pluck('count', 'category');

        $total = $data->sum();

        return response()->json([
            "series" => $data->values()->toArray(),
            "labels" => $data->keys()->toArray(),
            "total" => $total
        ]);
    }

    private function mapStatusData($departments, $statusData)
    {
        $mapped = [];
        foreach ($departments as $dept) {
            $mapped[] = $statusData[$dept] ?? 0;
        }
        return $mapped;
    }

    public function getHazardDepartment(Request $request)
    {
        $data = Hazard_Report::with('division')
            ->whereYear('created_at', $request->year)
            ->whereIn('dept_id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
            ->selectRaw('dept_id, status, COUNT(*) as count')
            ->groupBy('dept_id', 'status')
            ->orderBy('dept_id')
            ->orderBy('status')
            ->get();

        $departments = [];
        $seriesData = [
            'OPEN' => [],
            'CLOSED' => [],
            'ONPROGRESS' => []
        ];

        foreach ($data as $item) {
            $deptName = $item->division->division ?? 'Unknown';
            if ($deptName == 'Healt, Safety & Environment') {
                $deptName = 'HSE';
            }

            if (!in_array($deptName, $departments)) {
                $departments[] = $deptName;
            }

            $seriesData[$item->status][$deptName] = $item->count;
        }

        usort($departments, function($a, $b) {
            $order = ['HSE', 'Produksi', 'Maintenance', 'Engineering', 'QA', 'HRD', 'Finance', 'IT', 'Logistik', 'Purchasing', 'GA'];
            $posA = array_search($a, $order);
            $posB = array_search($b, $order);
            return $posA - $posB;
        });

        $series = [
            [
                'name' => 'Open',
                'data' => $this->mapStatusData($departments, $seriesData['OPEN'])
            ],
            [
                'name' => 'Closed',
                'data' => $this->mapStatusData($departments, $seriesData['CLOSED'])
            ],
            [
                'name' => 'On Progress',
                'data' => $this->mapStatusData($departments, $seriesData['ONPROGRESS'])
            ]
        ];

        return response()->json([
            "categories" => $departments,
            "series" => $series
        ]);
    }
}
