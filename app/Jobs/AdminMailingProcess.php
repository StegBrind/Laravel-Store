<?php

namespace App\Jobs;

use App\Notifications\AdminSendNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class AdminMailingProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var $users - Users to send mail
     * @var $notification - Notification to send
     * @var $last_history_notification - Notification in DB to update after successful sending
     */
    protected $users, $notification, $last_history_notification;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $users, AdminSendNotification $notification, Model $last_history_notification)
    {
        $this->users = $users;
        $this->notification = $notification;
        $this->last_history_notification = $last_history_notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send($this->users, $this->notification);
        $this->last_history_notification->update(['done' => 1]);
    }
}
