<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Conversation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
        [
            'user_ids', 'messages', 'token'
        ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_ids';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    public static function findOrCreate($user_id1, $user_id2)
    {
        $conversation_record = self::tryFindByUserIds($user_id1, $user_id2);
        if ($conversation_record === null)
        {
            $conversation_num = "$user_id1-$user_id2";
            $conversation_record = self::query()->create(
                [
                    'user_ids' => $conversation_num,
                    'token' => Str::random(50),
                    'messages' => ''
                ]);
            DB::update("UPDATE users SET conversation_list = CONCAT(conversation_list, '$conversation_num,') WHERE id = $user_id1");
            DB::update("UPDATE users SET conversation_list = CONCAT(conversation_list, '$conversation_num,') WHERE id = $user_id2");
        }
        return $conversation_record;
    }

    public static function tryFindByUserIds($user_id1, $user_id2)
    {
        return self::query()->where('user_ids', '=', "$user_id1-$user_id2")->
        orWhere('user_ids', '=', "$user_id2-$user_id1")->first();
    }
}
