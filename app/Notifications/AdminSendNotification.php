<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminSendNotification extends Notification
{
    use Queueable;

    private $letter;

    /**
     * Create a new notification instance.
     *
     * @param $letter
     */
    public function __construct($letter)
    {
        $this->letter = $letter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)->view('email.custom_email', ['content' => $this->letter['text']])
            ->subject($this->letter['subject']);
    }
}
