<?php

namespace App\Helpers;

use Kreait\Firebase\Factory;

class FirebaseHelper
{
    public static function messaging()
    {
        $factory = (new Factory)->withServiceAccount(storage_path(env('FIREBASE_PRIVATE_KEY')));
        return $factory->createMessaging();
    }
}