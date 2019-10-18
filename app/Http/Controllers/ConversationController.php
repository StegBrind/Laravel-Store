<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\User;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function talk($user_id)
    {
        $user_id1 = Auth::id();
        if ($user_id1 == $user_id) return abort(404);
        User::query()->findOrFail($user_id);
        $conversation_record = Conversation::findOrCreate($user_id1, $user_id);
        $token = $conversation_record->token;
        $messages = $conversation_record->messages;
        return view('conversation.talk',
            [
                'companion_name' => User::query()->find($user_id)['name_surname'],
                'user_ids' => "$user_id1-$user_id",
                'token' => $token,
                'messages' => $messages
            ]);
    }
    public function showList()
    {
        $conversation_list = Auth::user()['conversation_list'];
        if ($conversation_list == '')
            return view('conversation.list', ['conversation_list' => '<h4 style="text-align: center">Вы еще не совершали никаких переписок.</h4>']);
        $conversation_list = explode(',', $conversation_list);
        array_pop($conversation_list);
        $html_list = '';
        $my_id = Auth::id();
        $conversation_list = Conversation::query()->findMany($conversation_list);
        foreach ($conversation_list as $conversation_record)
        {
            if ($conversation_record->messages != '')
            {
                $user_ids_arr = explode('-', $conversation_record->user_ids);
                $companion_id = $user_ids_arr[0] == $my_id ? $user_ids_arr[1] : $user_ids_arr[0];
                $companion_name = User::query()->find($companion_id)->name_surname;
                $html_list .= "<li><a href='" . url("conversation/talk/$companion_id") . "'>Переписка с $companion_name</a></li>";
            }
        }
        if ($html_list == '') $html_list = '<h4 style="text-align: center">Вы еще не совершали никаких переписок.</h4>';
        return view('conversation.list', ['conversation_list' => $html_list]);
    }
}
