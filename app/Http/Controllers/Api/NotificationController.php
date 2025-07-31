<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FirebaseHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Messaging\CloudMessage;
// use Google\Auth\OAuth2;
// use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Messaging\Notification;

class NotificationController extends Controller
{
    //

    public function sendNotif(Request $request){
        $projectId = env('FIREBASE_PROJECT_ID');

        $deviceTokens = $request->devices;
        $messaging = FirebaseHelper::messaging();
        $notification = Notification::create($request->title, $request->body );

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData([
                'screen' => $request->screen ?? 'default',
                'key' => $request->detail_id ?? null
            ]);

        $report = $messaging->sendMulticast($message, $deviceTokens);

        return ResponseHelper::jsonSuccess('notification', [
            'success' => $report->successes()->count(),
            'failure' => $report->failures()->count()
        ]);

    }

    public function sendBroadcast(Request $request){
        $deviceTokens = $request->devices;
        $messaging = FirebaseHelper::messaging();
        $notification = Notification::create($request->title, $request->body );

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'custom_key' => 'custom_value'
            ])->withChangedTarget('topic', 'global');

        $report = $messaging->send($message);

        return ResponseHelper::jsonSuccess('notification send to all', []);

    }

    public function register_token(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                    'token'     => 'required'
            ]);
            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }
            $token = $request->token;
            $id = Auth::guard('api')->user()->id;
            User::find($id)->update([
                'fcm_token' => $token
            ]);
            return ResponseHelper::jsonSuccess('Token Updated', []);
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }

    }


}
