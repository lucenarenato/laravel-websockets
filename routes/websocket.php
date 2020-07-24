<?php
use App\Count;
use App\User;
use App\Message;
use Carbon\Carbon;
use Swoole\Http\Request;
use App\Services\WebSocket\WebSocket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Services\Websocket\Facades\Websocket as WebsocketProxy;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

WebsocketProxy::on('connect', function (WebSocket $websocket, Request $request) {
    
    $websocket->setSender($request->fd);
    $websocket->loginUsing(auth('api')->user());
});

WebsocketProxy::on('room', function (WebSocket $websocket, $data) {
    if ($userId = $websocket->getUserId()) {
        $user = User::find($userId);
        
        if (empty($data['roomid'])) {
            return;
        }
        $roomId = $data['roomid'];
        
        Redis::hset('socket_id', $user->id, $websocket->getSender());
        
        $count = Count::where('user_id', $user->id)->where('room_id', $roomId)->first();
        $count->count = 0;
        $count->save();
        
        $room = Count::$ROOMLIST[$roomId];
        $websocket->join($room);
        
        Log::info($user->name . 'Entre na sala:' . $room);
        
        $roomUsersKey = 'online_users_' . $room;
        $onelineUsers = Cache::get($roomUsersKey);
        $user->src = $user->avatar;
        if ($onelineUsers) {
            $onelineUsers[$user->id] = $user;
            Cache::forever($roomUsersKey, $onelineUsers);
        } else {
            $onelineUsers = [
                $user->id => $user
            ];
            Cache::forever($roomUsersKey, $onelineUsers);
        }
        
        $websocket->to($room)->emit('room', $onelineUsers);
    } else {
        $websocket->emit('login', 'Entre para entrar na sala de chat');
    }
});

WebsocketProxy::on('message', function (WebSocket $websocket, $data) {
    if ($userId = $websocket->getUserId()) {
        $user = User::find($userId);
        
        $msg = $data['msg'];
        $img = $data['img'];
        $roomId = intval($data['roomid']);
        $time = $data['time'];
        
        if((empty($msg)  && empty($img))|| empty($roomId)) {
            return;
        }
        
        Log::info($user->name . 'no quarto' . $roomId . 'Postar uma mensagem em: ' . $msg);
        
        if (empty($img)) {
            $message = new Message();
            $message->user_id = $user->id;
            $message->room_id = $roomId;
            $message->msg = $msg;  // 文本消息
            $message->img = '';  // 图片消息留空
            $message->created_at = Carbon::now();
            $message->save();
        }
        
        $room = Count::$ROOMLIST[$roomId];
        $messageData = [
            'userid' => $user->email,
            'username' => $user->name,
            'src' => $user->avatar,
            'msg' => $msg,
            'img' => $img,
            'roomid' => $roomId,
            'time' => $time
        ];
        $websocket->to($room)->emit('message', $messageData);
        
        $userIds = Redis::hgetall('socket_id');
        foreach ($userIds as $userId => $socketId) {
            
            $result = Count::where('user_id', $userId)->where('room_id', $roomId)->first();
            if ($result) {
                $result->count += 1;
                $result->save();
                $rooms[$room] = $result->count;
            } else {
                
                $count = new Count();
                $count->user_id = $user->id;
                $count->room_id = $roomId;
                $count->count = 1;
                $count->save();
                $rooms[$room] = 1;
            }
            $websocket->to($socketId)->emit('count', $rooms);
        }
    } else {
        $websocket->emit('login', 'Entre para entrar na sala de chat');
    }
});

WebsocketProxy::on('roomout', function (WebSocket $websocket, $data) {
    roomout($websocket, $data);
});

WebsocketProxy::on('disconnect', function (WebSocket $websocket, $data) {
    roomout($websocket, $data);
    $websocket->logout();
});

function roomout(WebSocket $websocket, $data) {
    if ($userId = $websocket->getUserId()) {
        $user = User::find($userId);
        if (empty($data['roomid'])) {
            return;
        }
        $roomId = $data['roomid'];
        $room = Count::$ROOMLIST[$roomId];
        
        $roomUsersKey = 'online_users_' . $room;
        $onelineUsers = Cache::get($roomUsersKey);
        if (!empty($onelineUsers[$user->id])) {
            unset($onelineUsers[$user->id]);
            Cache::forever($roomUsersKey, $onelineUsers);
        }
        $websocket->to($room)->emit('roomout', $onelineUsers);
        Log::info($user->name . 'Sair da sala: ' . $room);
        $websocket->leave($room);
    } else {
        $websocket->emit('login', 'Entre para entrar na sala de chat');
    }
}

WebsocketProxy::on('login', function (WebSocket $websocket, $data) {
    if ($userId = $websocket->getUserId()) {
        $user = User::find($userId);
        
        Redis::hset('socket_id', $user->id, $websocket->getSender());
        
        $rooms = [];
        foreach (Count::$ROOMLIST as $id => $name) {
            
            $result = Count::where('user_id', $user->id)->where('room_id', $id)->first();
            if ($result) {
                $rooms[$name] = $result->count;
            } else {
                $count = new Count();
                $count->user_id = $user->id;
                $count->room_id = $id;
                $count->count = 0;
                $count->save();
                $rooms[$name] = 0;
            }
        }
        
        Log::info($user->name . 'login bem sucedido');
        
        $websocket->emit('count', $rooms);
    } else {
        $websocket->emit('login', 'Entre para entrar na sala de chat');
    }
});