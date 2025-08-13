<?php

namespace App\Helpers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseHelper
{
    public static function messaging()
    {
        $factory = (new Factory)->withServiceAccount(storage_path(env('FIREBASE_PRIVATE_KEY')));
        return $factory->createMessaging();
    }


    public static function sendByToken($title="New Notification", $desc="This Notification Description Body", $devices = [], $screen=null, $detail_id = null){
        $deviceTokens = $devices;
        $messaging = self::messaging();
        $notification = Notification::create($title, $desc );

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData([
                'screen' => $screen ?? 'default',
                'key' => $detail_id ?? null
            ]);

        $report = $messaging->sendMulticast($message, $deviceTokens);

        return $report;
    }
}