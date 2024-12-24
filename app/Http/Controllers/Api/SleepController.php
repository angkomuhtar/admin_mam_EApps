<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Sleep;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class SleepController extends Controller
{
    //
    public function index()
    {
        try {
            $data = Sleep::where('user_id', Auth::guard('api')->user()->id)->orderBy('date', 'desc')->get();
            foreach ($data as $item) {
                if ($item->attachment != null && !filter_var($item->attachment, FILTER_VALIDATE_URL)) {
                    $item->imagesUrl = asset("{$item->attachment}");
                }else{
                    $item->imagesUrl = $item->attachment;
                }
            }
            return ResponseHelper::jsonSuccess('Berhasil', $data);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }
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

            $fileName = '';
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = Auth::guard('api')->user()->username.now()->format('His');
                // $fileFullPath = 'images/sleeps/'.$fileName; 
                // Storage::disk('public')->put($fileFullPath, file_get_contents($file));
                // $filename_db = $fileFullPath;   

                $public_id = date('Y-m-d_His').'_'.$fileName;
                $url = cloudinary()->upload($file->getRealPath(), [
                    "public_id" => $fileName,
                    "folder"    => "sleeps",
                    'transformation' => [
                      'quality' => "auto",
                      'fetch_format' => "auto"
                    ]
                ])->getSecurePath();
            }

            $today = Carbon::now()->setTimeZone('Asia/Makassar')->format('Y-m-d');
            $insert = Sleep::insert([
                'user_id'=> Auth::guard('api')->user()->id,
                'start'=> $request->start,
                'end' => $request->end,
                'date' => $today,
                'stage'=> $request->stage,
                'attachment' => cloudinary()->getUrl("sleeps/{$fileName}")
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
