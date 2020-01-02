<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AdminNotificationEvent
{
    use Dispatchable;

    public string $text_message;

    /**
     * Create a new event instance.
     *
     * @param string $text_message
     */
    public function __construct(string $text_message)
    {
        $this->text_message = $text_message;
    }
}
