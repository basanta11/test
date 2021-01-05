<?php

namespace App\Helpers;

use App\Events\ActionNotification;
use Illuminate\Support\Facades\Storage;
use Str;

class NotificationHelper
{
    public function notifyOne($to,$model,$model_id, $model_link, $notification)
    {
        event(new ActionNotification(auth()->user(), $to, $model,$model_id,$model_link, $notification));
    }

    public function notifyMany($to,$model,$model_id, $model_link, $notification)
    {
        foreach ($to as $user)
            event(new ActionNotification(auth()->user(), $user,$model,$model_id,$model_link, $notification));
    }

}