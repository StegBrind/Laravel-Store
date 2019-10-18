<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminNotificationHistory extends Model
{
    public $timestamps = false;

    protected $fillable =
    [
        'done', 'subject', 'message_content'
    ];

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'notification_history';

}
