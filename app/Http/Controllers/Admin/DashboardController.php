<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Clock;
use App\Models\Sleep;
use App\Models\Profile;
use App\Models\Employee;
use App\Models\ViewClock;
use Illuminate\Support\Str;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;

class DashboardController extends Controller
{

    public function index (Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        $employee= Employee::select('division_id', DB::raw('count(*) as post_count'))->with('division')
        ->whereNotIn('division_id', [11, 11001])
        ->whereHas('user', function($query){
            $query->where('status','Y');
        })
        ->groupBy('division_id')
        ->get();

        $hadir=  DB::table('v_clock')
        ->select('shift','value', 'site','division', DB::raw('count(*) as total'))
        ->where('date', $today)
        ->groupBy('shift', 'value', 'site', 'division')
        ->orderBy('division')
        ->get();

        $data = $hadir->map(function($item){
            if ($item->shift == 'Day Shift') {
                $item->type = 'day';
            }else if($item->shift == 'Night Shift'){
                $item->type = 'night';
            }else{
                $item->type = 'office';
            }
            return $item;
        });
        $groupped= $data->groupBy('type');


        $dataSleep = User::where('roles','<>', 'superadmin')
          ->with('employee','absen.shift', 'profile', 'employee.division', 'employee.position', 'employee.category', 'employee.work_schedule', 'sleep')
          ->whereHas('profile', function ($query) use ($request){
            $query->where('name', 'LIKE', '%'.$request->name.'%')->whereIn('user_id', ['24','3613','3622','3623','3625','3630','3637','3651','3659','3683','3688','3690','3710','3719','3720','3722','3740','3762','3789','3792','3796','3797','3809','3813','3817','3835','3863','3866','3872','3884','3888','3900','3922','3936','3944','3956','3960','3962','3985','3987','4014','4034','4035','4036','4048','4059','4061','4079','4083','4085','4113','4114','4117','4118','4123','4131','4166','4177','4183','4191','4227','4234','4248','4256','4260','4266','4283','4286','4298','4305','4310','4312','4353','4367','4376','4391','4394','4423','4437','4440','4444','4450','4451','4454','4480','4481','4496','4497','4501','4524','4544','4546','4559','4566','4567','4582','4586','4591','4631', '4514', '4798', '5132']);
          })
          ->whereHas('employee', function ($query) use ($request){
            $query->where('contract_status', 'ACTIVE')->where('division_id', '<>', 11)->where('division_id', '<', 100);
          })
          ->with('absen', function ($query) use ($today) {
            $query->where('date', $today);
          })
          ->with(['sleep'=> function ($query) use ($today){
            $query->where('date', $today);
          }]);

        $hadir = $dataSleep->get()->filter(function($item){
            if ($item->absen->isNotEmpty()) {
                return $item;
            }
        });

        $groupSleep = $hadir->filter(function($item){
            if ($item->sleep->isNotEmpty()) {
                $start = Carbon::createFromFormat("Y-m-d H:i:s", $item->sleep[0]->start);
                $end = Carbon::createFromFormat("Y-m-d H:i:s", $item->sleep[0]->end);
                $duration = $start->diffInMinutes($end);
                if (6 * 60 <= $duration) {
                    $item->sleepType = 'baik';
                }elseif(5 * 60 <= $duration){
                    $item->sleepType = 'cukup';
                }else{
                    $item->sleepType = 'kurang';
                }
                return $item;
            }
        });


        $groupSleepType = $groupSleep->groupBy('sleepType');
        $totalHadir = $hadir->count();
        $tidurBaik = isset($groupSleepType['baik']) ? $groupSleepType['baik']->count() : 0;
        $tidurCukup = isset($groupSleepType['cukup']) ? $groupSleepType['cukup']->count() : 0;
        $tidurKurang = isset($groupSleepType['kurang']) ? $groupSleepType['kurang']->count() : 0;
        $tidakInput = $totalHadir - $tidurBaik + $tidurCukup + $tidurKurang;

        $tidurBaikPercentage = $totalHadir > 0 ? round($tidurBaik / $totalHadir * 100) : 0;
        $tidurCukupPercentage = $totalHadir > 0 ? round($tidurCukup / $totalHadir * 100) : 0;
        $tidurKurangPercentage = $totalHadir > 0 ? round($tidurKurang / $totalHadir * 100) : 0;
        $tidakInputPercentage = $totalHadir > 0 ? round($tidakInput / $totalHadir * 100) : 0;

        
        $sleepChart = [
            'totalHadir'=> $totalHadir,
            'baik' => $tidurBaik,
            'cukup' => $tidurCukup,
            'kurang' => $tidurKurang,
            'radialValue' => [
                    $tidurBaikPercentage,
                    $tidurCukupPercentage,
                    $tidurKurangPercentage,
                    $tidakInputPercentage
            ],
            'barChart' => [
                'series' => [
                    'fit' => [
                        'name' => "Fit to Works",
                        'data' => [20, 55, 57, 56, 61, 58, 63]
                    ],
                    'supervision' => [
                        'name' => "Dalam pengawasan",
                        'data' => [76, 85, 101, 98, 87, 105, 91],
                    ],
                    'rest' =>[
                        'name' => "Istirahat",
                        'data' => [35, 41, 36, 26, 45, 48, 52],
                    ]
                ]
            ]

        ];
        return view('pages.dashboard.index', [
            'pageTitle' => 'Analytic Dashboard',
            'division_count' => $employee,
            'day_count' => $groupped['day'] ?? [],
            'night_count' => $groupped['night'] ?? [],
            'office_count' => $groupped['office'] ?? [],
            'sleepChart' => collect($sleepChart)
        ]);
    }

    public function rekap_hadir(Request $request){
        $hadir=  DB::table('v_clock')
        ->select('shift','value', 'site','division', DB::raw('count(*) as total'))
        ->where('date', $request->tanggal)
        ->where('site', $request->project)
        ->where('shift','LIKE', '%'.$request->shift.'%')
        ->groupBy('shift', 'value', 'site', 'division')
        ->orderBy('division')->get();
        return DataTables::of($hadir)->toJson();
    }

    public function import_data(Request $request){
        // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
        $file = public_path('images/BBEDATA.xlsx');
        $import = new UsersImport;
        Excel::import($import, $file);
        $data_double = [];
        $data_null = [];

        // return $request->id;
        $offset = $request->offset;
        $start = ($request->page - 1) * $offset;
        $item = $import->data;
        for ($i=$start; $i < $start+$offset; $i++) { 
         
            // echo $i." ";
            if ($i + 1 >= count($item)) {
                break;
            }
            $data = Profile::where('name', '=',$item[$i]['nama'])->with('user')
                ->whereHas('user', function($query){
                    $query->where('status','Y');
                })->get();
            if (count($data) > 1) {
               array_push($data_double,[count($data), $item[$i]['nama']]);
            }else if (count($data) == 0) {
                array_push($data_null,[count($data), $item[$i]['nama']]);
            }else if (count($data) == 1) {
                // return $data[0]->user_id;
                $employee = Employee::where('user_id', $data[0]->user_id)->update([
                    'nip' => $item[$i]['nik'],
                    'doh' => gmdate( 'Y-m-d', (($item[$i]['doh'] - 25569) * 86400))
                ]);
                $profile = Profile::find($data[0]->id)->update([
                    'card_id' => $item[$i]['card_id'],
                    'kk' => $item[$i]['kk'],
                    'tmp_lahir' => $item[$i]['tmp'],
                    'tgl_lahir' => gmdate( 'Y-m-d', (($item[$i]['tgl'] - 25569) * 86400)) ,
                    'gender' => $item[$i]['gender'],
                    'religion' => $item[$i]['religion'],
                    'marriage' => $item[$i]['marriage'],
                    'id_addr' => $item[$i]['id_addr'],
                    'live_addr' => $item[$i]['live_addr'],
                    'phone' => $item[$i]['phone']
                ]);
            }
        }

        return [$data_double, $data_null];

    }

    public function update_jam(){
        $data= Sleep::all();

        foreach ($data as $key => $value) {
            if ($value->date == null) {
                $date= Carbon::parse($value->end)->format('Y-m-d');
                $update = Sleep::find($value->id)->update([
                    'date' => $date
                ]);
            }
        }

        return Sleep::all();
    }
}
