<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function getAll()
    {
        return json_encode(
            Admin::query()->select(['notifications', 'last_read_notification_index'])->where('id', '=', Admin::id())->
            get()->toArray()[0]
        );
    }

    public function getFromLastReadNotificationIndex(Request $request)
    {
        $indexFrom = 'last_read_notification_index';
        if ($request->has('indexFrom')) $indexFrom = $request->indexFrom;
        try
        {
            $unread_notifications = array_values(Admin::query()->where('id', '=', Admin::id())->selectRaw("
                JSON_EXTRACT(notifications, CONCAT('$[', $indexFrom + 1, ' to ', JSON_LENGTH(notifications) - 1, ']'))
            ")->get()->toArray()[0])[0];
            if ($unread_notifications == '') return '[]';
            return $unread_notifications;
        }
        catch (\Exception $e)
        {
            return '[]';
        }
    }

    public function updateLastReadNotification()
    {
        DB::update('UPDATE admin SET last_read_notification_index = JSON_LENGTH(notifications) - 1 WHERE id = ' . Admin::id());
    }
}