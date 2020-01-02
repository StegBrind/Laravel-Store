<?php


namespace App\Http\Controllers\Admin;

use App\AdminNotificationHistory;
use App\Http\Controllers\Controller;
use App\Jobs\AdminMailingProcess;
use App\Notifications\AdminSendNotification;
use App\User;
use Illuminate\Http\Request;

class MailingUsersController extends Controller
{

    public function post(Request $request)
    {
        if (trim($request->subject) == '') $request->subject = 'Без темы';
        $users = $request->emails_confirmed == 'true' ? User::query()->whereNotNull('email_verified_at')->get() : User::all();

        AdminNotificationHistory::query()->insert(['subject' => $request->subject, 'content_message' => $request->message_text]);

        AdminMailingProcess::dispatch(
            $users,
            new AdminSendNotification(['subject' => $request->subject, 'text' => $request->message_text]),
            AdminNotificationHistory::query()->latest('id')->first()
        );
    }
}