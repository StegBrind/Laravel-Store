<?php

namespace App\Listeners;

use App\Events\AdminNotificationEvent;
use Illuminate\Support\Facades\DB;

class SendAdminNotification
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AdminNotificationEvent $event)
    {
        DB::update('
        UPDATE admin SET notifications = JSON_ARRAY_APPEND(notifications, \'$\', JSON_ARRAY("'. $event->text_message . '", '. round(microtime() * 1000) .')) WHERE name = 'Админ Владелец'
        ');
    }
}
