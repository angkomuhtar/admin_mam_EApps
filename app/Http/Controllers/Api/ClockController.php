<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Clock;
use App\Models\Employee;
use App\Models\ReportOperation;
use App\Models\ReportParam;
use App\Models\Shift;
use App\Models\Sleep;
use App\Models\Version;
use App\Models\Watchdist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClockController extends Controller
{
    public $today;

    // Constructor to initialize the variable or perform any other setup
    public function __construct()
    {
        $this->today = Carbon::now()->setTimeZone('Asia/Makassar');
    }
    public function index(){
        try {
            $day = $this->today->format('d');
            $startDay = $this->today->format('d') > 25 ? $this->today->format('Y-m-26') : $this->today->subMonths(1)->format('Y-m-26');
            $endDay = Carbon::createFromFormat('Y-m-d', $startDay)->addMonths(1)->format('Y-m-25');
            $clock = Clock::with('shift')->whereBetween('date', [$startDay, $endDay])->where('user_id', Auth::guard('api')->user()->id)->orderBy('date', 'desc')->get();
            $clock = $clock->map(function ($clock) {
                $clock['late'] = $clock->late;
                $clock['early'] = $clock->early;
                return $clock;
            });
            return ResponseHelper::jsonSuccess('success get data', $clock);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function history($month){
        try {
            $selectedDay = Carbon::createFromFormat('Y-m-d', $month);
            $day = $selectedDay->format('d');
            $startDay = $selectedDay->format('Y-m-26');
            $endDay = Carbon::createFromFormat('Y-m-d', $startDay)->addMonths(1)->format('Y-m-25');
            $clock = Clock::with('shift')->whereBetween('date', [$startDay, $endDay])->where('user_id', Auth::guard('api')->user()->id)->orderBy('date', 'desc')->get();
            $clock = $clock->map(function ($clock) {
                $clock['late'] = $clock->late;
                $clock['early'] = $clock->early;
                return $clock;
            });
            return ResponseHelper::jsonSuccess('success get data', $clock);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function home(Request $request){
        try {
            $day = $this->today->format('d');
            $startDay = $this->today->format('d') > 25 ? $this->today->format('Y-m-26') : $this->today->subMonths(1)->format('Y-m-26');
            $endDay = Carbon::createFromFormat('Y-m-d', $startDay)->addMonths(1)->format('Y-m-25');
            // return $startDay.' '. $endDay;
            $absen = Clock::whereBetween('date', [$startDay, $endDay])->where('user_id', Auth::guard('api')->user()->id);
            $hadir = $absen->where('status', 'H')->count();
            $rekap = [
                'hadir'=>$hadir,
                'alpa'=> 0,
                'izin' => 0,
                'start' => $startDay,
                'end' => $endDay,
            ];

            $work_hours = Shift::whereColumn('start', '>', 'end')->get();
            $wh_id = $work_hours->pluck('id')->toArray();
            $today = Clock::where(function($query) use($request) {
                $query->where('user_id',Auth::guard('api')->user()->id)
                ->where('date', date('Y-m-d'));
            })
            ->orWhere(function($query) use($request, $wh_id) {
                $query->where('user_id',Auth::guard('api')->user()->id)
                ->where('date', date('Y-m-d', strtotime('-1 day')))
                ->whereNull('clock_out')
                ->whereIn('work_hours_id', $wh_id);
            })
            ->get();
            $data = collect(['rekap'=>$rekap, 'today'=>$today]);
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function rekap(Request $request){
        try {
            $day = $this->today->format('d');
            $startDay = $this->today->format('d') > 25 ? $this->today->format('Y-m-26') : $this->today->subMonths(1)->format('Y-m-26');
            $endDay = Carbon::createFromFormat('Y-m-d', $startDay)->addMonths(1)->format('Y-m-25');
            $absen = Clock::whereBetween('date', [$startDay, $endDay])->where('user_id', Auth::guard('api')->user()->id);
            $hadir = $absen->where('status', 'H')->count();
            $alpha = $absen->where('status', 'A')->count();
            $izin = $absen->where('status', 'I')->count();
            $rekap = [
                'hadir'=>$hadir,
                'alpa'=> $alpha,
                'izin' => $izin,
                'start' => $startDay,
                'end' => $endDay,
            ];

            $data = collect(['rekap'=>$rekap]);
            return ResponseHelper::jsonSuccess('success get data', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function today(Request $request){
        try {
            $date_today =Carbon::now()->setTimeZone('Asia/Makassar')->format('Y-m-d');
            $work_hours = Shift::whereColumn('start', '>', 'end')->orWhere('start', '<=', '03:00:00')->get();
            $wh_id = $work_hours->pluck('id')->toArray();
            $today = Clock::where(function($query) use($request) {
                $query->where('user_id',Auth::guard('api')->user()->id)
                ->where('date', $this->today->format('Y-m-d'));
            })
            ->orWhere(function($query) use($request, $wh_id) {
                $query->where('user_id',Auth::guard('api')->user()->id)
                ->where('date', $this->today->subDays(1)->format('Y-m-d'))
                ->whereNull('clock_out')
                ->whereIn('work_hours_id', $wh_id);
            })
            ->first();
            $sleep = Sleep::where('user_id', Auth::guard('api')->user()->id)
            ->where('date', $date_today)
            ->get();
            if ($today) {
                $today['late'] = $today->late;
                $today['early'] = $today->early;
            }
            $today['sleep'] = $sleep;
            return ResponseHelper::jsonSuccess('success get data', $today);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function location(){
        try {
            $location = Employee::where('user_id', Auth::guard('api')->user()->id)->first();
            return ResponseHelper::jsonSuccess('success get location', $location->lokasi);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }


    public function clock(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'shift'     => 'required',
                'date'  => 'required',
                'time' => 'required',
                'location' => 'required',
                'version' => 'required',
                'platform' => 'required',
            ],[
                'required' => ':attribute tidak boleh kosong'
              ]);

            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }

            $versionCheck = Version::where('device', $request->platform)->first();

            // Open On Sunday
            if ($request->version < $versionCheck->version) {
                $validator->errors()->add('jam_tidur', 'versi aplikasi tidak sesuai, segera melakukan update');
                return ResponseHelper::jsonError($validator->errors(), 422);
            }

            if ($request->type == 'in') {
                $inlist = Watchdist::where('user_id', Auth::guard('api')->user()->id)->where('status', 'Y')->exists();
                $sleep = Sleep::where('user_id', Auth::guard('api')->user()->id)->where('date', $request->date)->exists();

                if ($inlist && !$sleep) {
                    $validator->errors()->add('jam_tidur', 'Anda belum menginput jam tidur');
                    return ResponseHelper::jsonError($validator->errors(), 422);
                }
                $exist = Clock::where('user_id', Auth::guard('api')->user()->id)->where('date', $request->date)->exists();
                if ($exist) {
                    return ResponseHelper::jsonSuccess('Berhasil Absen Masuk');
                }

                return DB::transaction(function() use($request){
                    $insert = Clock::insert([
                        'user_id'=> Auth::guard('api')->user()->id,
                        'clock_location_id'=> $request->location,
                        'date' => $this->today->format('Y-m-d'),
                        'clock_in'=>$this->today->format('Y-m-d H:i:s'),
                        'work_hours_id'=> $request->shift,
                        'status' => 'H'
                    ]);

                    $employee = Auth::guard('api')->user()?->employee;

                    $attendance = [
                        'check_in' => $this->today->format('Y-m-d H:i:s')
                    ];

                    $parameters = ReportParam::query()
                        ->with('report_param_details')
                        ->where('division_id', $employee->division_id)
                        ->get();

                    if(count($parameters) > 0){
                        $score = $this->calculateScore($attendance, $parameters, $request->shift, 'check_in');

                        if(count($score['details']) > 0)
                            foreach($score['details'] as $detail){
                                ReportOperation::create([
                                    'report_param_id' => $detail['parameter_id'],
                                    'report_param_detail_id' => $detail['parameter_detail_id'],
                                    'employee_id' => $employee->id,
                                    'point' => $detail['score'],
                                    'date' => $this->today->format('Y-m-d'),
                                ]);
                            }
                    }

                    return $insert;
                });

                if ($insert) {
                    return ResponseHelper::jsonSuccess('Berhasil Absen Masuk', $insert);
                }else{
                    return ResponseHelper::jsonError('error absen', 400);
                }
            }elseif ($request->type == 'out'){
                return DB::transaction(function() use($request){
                    $clock = Clock::where('user_id', Auth::guard('api')->user()->id)
                        ->where('date', $request->date)
                        ->update(['clock_out' => $this->today->format('Y-m-d H:i:s')]);

                    $employee = Auth::guard('api')->user()?->employee;

                    $attendance = [
                        'check_out' => $this->today->format('Y-m-d H:i:s')
                    ];

                    $parameters = ReportParam::query()
                        ->with('report_param_details')
                        ->where('division_id', $employee->division_id)
                        ->get();

                    if(count($parameters) > 0){
                        $score = $this->calculateScore($attendance, $parameters, $request->shift, 'check_out');

                        if(count($score['details']) > 0)
                            foreach($score['details'] as $detail){
                                ReportOperation::create([
                                    'report_param_id' => $detail['parameter_id'],
                                    'report_param_detail_id' => $detail['parameter_detail_id'],
                                    'employee_id' => $employee->id,
                                    'point' => $detail['score'],
                                    'date' => $this->today->format('Y-m-d'),
                                ]);
                            }
                    }

                    return $clock;
                });
                if ($clock) {
                    return ResponseHelper::jsonSuccess('Berhasil Absen Pulang', $clock);
                }else{
                    return ResponseHelper::jsonError('error on update', 400);
                }
            }
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function shift(){
        try {
            $today = $this->today->format('N');
            $work_hours = Shift::where('wh_code', Auth::guard('api')->user()->employee->wh_code)->where('days', 'like', '%'.$today.'%')->get();
            return ResponseHelper::jsonSuccess('success get location', $work_hours);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    private function calculateScore($attendance, $parameters, $shiftId, $type)
    {
        Log::info('Calculating Score', [
            'attendance' => $attendance,
            'shiftId' => $shiftId,
            'type' => $type
        ]);

        $totalScore = 0;
        $results = [];

        foreach ($parameters as $parameter) {
            foreach ($parameter->report_param_details as $detail) {
                if (!is_null($detail->shift_id) && $detail->shift_id != $shiftId) {
                    continue;
                }

                if (!is_null($detail->type) && strtolower(trim($detail->type)) !== strtolower(trim($type))) {
                    continue;
                }

                $key = trim($detail->key);
                $value = $attendance[$key] ?? null;

                Log::info('Evaluating detail', [
                    'detail_id' => $detail->id,
                    'key' => $key,
                    'value' => $value,
                    'operator' => $detail->operator,
                    'expected' => $detail->value,
                    'score' => $detail->score
                ]);

                $isPassed = $this->evaluate($value, $detail->operator, $detail->value);

                Log::info('Evaluation result: ' . ($isPassed ? 'PASSED' : 'FAILED'));

                if ($isPassed) {
                    $totalScore += $detail->score;

                    $results[] = [
                        'parameter' => $parameter->name,
                        'description' => $detail->description,
                        'passed' => $isPassed,
                        'score' => $isPassed ? $detail->score : 0,
                        'parameter_id' => $parameter->id,
                        'parameter_detail_id' => $detail->id,
                        'shift_id' => $detail->shift_id
                    ];
                }
            }
        }

        Log::info('Total Score: ' . $totalScore);

        return [
            'total_score' => $totalScore,
            'details' => $results,
        ];
    }

    private function evaluate($actual, $operator, $expected)
    {
        $operator = trim($operator);

        $isExpectedNull = is_null($expected)
            || trim($expected) === ''
            || strtolower(trim($expected)) === 'null';

        if ($operator === '!=') {
            if ($isExpectedNull) {
                return !is_null($actual) && $actual !== '';
            }

            return $actual != $expected;
        }

        if ($operator === '=') {
            if ($isExpectedNull) {
                return is_null($actual) || $actual === '';
            }

            return $actual == $expected;
        }

        if (!is_null($actual) && !is_null($expected) && $actual !== '' && $expected !== '') {
            try {
                $actualTime = Carbon::parse($actual);

                if (preg_match('/^\d{2}:\d{2}$/', $expected)) {
                    $expectedTime = $actualTime->copy()->setTimeFromTimeString($expected);
                } else {
                    $expectedTime = Carbon::parse($expected);
                }

                Log::info('Comparing time', [
                    'actual' => $actualTime->format('Y-m-d H:i:s'),
                    'expected' => $expectedTime->format('Y-m-d H:i:s'),
                    'operator' => $operator,
                ]);

                switch ($operator) {
                    case '<=':
                        return $actualTime->lte($expectedTime);

                    case '>=':
                        return $actualTime->gte($expectedTime);

                    case '<':
                        return $actualTime->lt($expectedTime);

                    case '>':
                        return $actualTime->gt($expectedTime);

                    default:
                        return false;
                }
            } catch (\Exception $e) {
                Log::error('Error parsing time: ' . $e->getMessage(), [
                    'actual' => $actual,
                    'expected' => $expected,
                    'operator' => $operator
                ]);

                return false;
            }
        }

        return false;
    }
}
