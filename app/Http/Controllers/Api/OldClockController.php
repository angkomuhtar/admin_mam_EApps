<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Clock;
use App\Models\Shift;
use App\Models\Sleep;
use App\Models\Employee;
use App\Models\Watchdist;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use App\Models\ClockLocation;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OldClockController extends Controller
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
                return [
                    "id" => $clock->id,
			        "clock_location_id" => $clock->clock_location_id,
			        "date" => $clock->date,
			        "clock_in" => $clock->clock_in ? Carbon::createFromFormat('Y-m-d H:i:s', $clock->clock_in)->format('H:i:s') : null,
                    "clock_out"=>$clock->clock_out ? Carbon::createFromFormat('Y-m-d H:i:s', $clock->clock_out)->format('H:i:s') : null,
                    "work_hours_id"=>$clock->work_hours_id,
                    "status"=>$clock->status,
                    "late"=> $clock->late,
                    "early"=>$clock->early,
                    "shift"=>$clock->shift
                ];
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
                'izin' => 0 
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
            ->get()->map(function($item){
                return [
                    "clock_location_id" => $item->clock_location_id,
                    "date"=>    $item->date,
                    "clock_in"=> $item->clock_in ? Carbon::createFromFormat('Y-m-d H:i:s', $item->clock_in)->format('H:i:s') : null,
                    "clock_out"=>$item->clock_out ? Carbon::createFromFormat('Y-m-d H:i:s', $item->clock_out)->format('H:i:s') : null,
                    "work_hours_id"=> $item->work_hours_id ,
                    "status"=> $item->status
                ];
            });
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
                'izin' => $izin 
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
            $work_hours = Shift::whereColumn('start', '>', 'end')->get();
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
                $today =  [
                    "clock_location_id"=>$today->clock_location_id,
                    "date"=>$today->date,
                    "clock_in"=> $today->clock_in ? Carbon::createFromFormat('Y-m-d H:i:s', $today->clock_in)->format('H:i:s') : null,
                    "clock_out"=>$today->clock_out ? Carbon::createFromFormat('Y-m-d H:i:s', $today->clock_out)->format('H:i:s') : null,
                    "work_hours_id"=>$today->work_hours_id,
                    "status"=>$today->status,
                    "shift" =>$today->shift,
                    "late" => $today->late,
                    "early" =>$today->early
                ];
            }
            $today['sleep'] = $sleep;
            return ResponseHelper::jsonSuccess('success get data', $today);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }

    public function location(){
        try {
            // $location = ClockLocation::All();
            // return ResponseHelper::jsonSuccess('success get location', $location);
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
            ],[
                'required' => ':attribute tidak boleh kosong'
              ]);

            if ($validator->fails()) {
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
                $insert = Clock::insert([
                    'user_id'=> Auth::guard('api')->user()->id,
                    'clock_location_id'=> $request->location,
                    'date' => $this->today->format('Y-m-d'),
                    'clock_in'=>$this->today->format('Y-m-d H:i:s'),
                    'work_hours_id'=> $request->shift,
                    'status' => 'H'
                ]);
                if ($insert) {
                    return ResponseHelper::jsonSuccess('Berhasil Absen Masuk', $insert);
                }else{
                    return ResponseHelper::jsonError('error absen', 400);
                }
            }elseif ($request->type == 'out'){
                $clock = Clock::where('user_id', Auth::guard('api')->user()->id)
                    ->where('date', $request->date)
                    ->update(['clock_out' => $this->today->format('Y-m-d H:i:s')]);
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




    
}
