<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\ViewClockSleep;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
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
        $endweek = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
        $startweek = Carbon::createFromFormat('Y-m-d', $date)->subDays(6)->format('Y-m-d');

        $dataSleepWeek = ViewClockSleep::with('user')->whereBetween('sleep_date',[$startweek, $endweek])
        ->whereHas('user', function($query){
            $query->has('smartwatch');
        })->get()->filter()->groupBy('date');

        $endDate = Carbon::parse($date);
        $startDate = Carbon::parse($date)->subDays(6);
        $data = array();
        $enough = array();
        $bad = array();
        $legend = array();
        $no = 0;
        while ($startDate->lte($endDate)){
            if (isset($dataSleepWeek[$startDate->format('Y-m-d')])) {
                $total = $dataSleepWeek[$startDate->format('Y-m-d')]->filter()->groupBy('sleep_cat');
                $fit[$no] = isset($total['FIT'])? $total['FIT']->count() : 0;
                $enough[$no] = isset($total['ENOUGH'])? $total['ENOUGH']->count() : 0;
                $bad[$no] = isset($total['BAD'])? $total['BAD']->count() : 0;
            }else{
                $fit[$no] = 0;
                $enough[$no] = 0;
                $bad[$no] = 0;
            }
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

}
