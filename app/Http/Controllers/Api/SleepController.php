<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Sleep;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SleepController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start'     => 'required',
                'end'  => 'required',
                'stage' => 'required',
                'attachment' => 'required'
            ]);
            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }

            $filename_db = '';
            if ($request->hasFile('attachment')) {
                $directory = 'images/sleeps/';
                $file = $request->file('attachment');
                $fileName = Auth::guard('api')->user()->username.now()->format('His').'.'.$file->getClientOriginalExtension();
                $fileFullPath = 'images/sleeps/'.$fileName; 
                Storage::disk('public')->put($fileFullPath, file_get_contents($file));
                $filename_db = $fileFullPath;   
            }
            $today = Carbon::now()->setTimeZone('Asia/Makassar')->format('Y-m-d');
            $insert = Sleep::insert([
                'user_id'=> Auth::guard('api')->user()->id,
                'start'=> $request->start,
                'end' => $request->end,
                'date' => $today,
                'stage'=> $request->stage,
                'attachment' => $filename_db
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
}
