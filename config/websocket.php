<?php

use SwooleTW\Http\Websocket\Facades\Websocket;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Websocket events for your application.
|
*/

/*Websocket::on('connect', function ($websocket, $request) {
    // in connect callback, illuminate request will be injected here
    $websocket->emit('message', 'welcome');
});

Websocket::on('disconnect', function ($websocket) {
    // this callback will be triggered when a websocket is disconnected
});

Websocket::on('example', function ($websocket, $data) {
    $websocket->emit('message', $data);
});

Websocket::on('test', 'ExampleController@method');

// sending to sender-client only
Websocket::emit('message', 'this is a test');

// sending to all clients except sender
Websocket::broadcast()->emit('message', 'this is a test');

// sending to all clients in 'game' room except sender
Websocket::broadcast()->to('game')->emit('message', 'nice game');

// sending to all clients in 'game1' and 'game2' rooms except sender
Websocket::broadcast()->to('game1')->to('game2')->emit('message', 'nice game');
Websocket::broadcast()->to(['game1', 'game2'])->emit('message', 'nice game');

// sending to all clients in 'game' including sender client
Websocket::to('game')->emit('message', 'enjoy the game');

// sending to individual socketid 1 (can't be sender)
Websocket::broadcast()->to(1)->emit('message', 'for your eyes only');

// sending to socketid 1 and 2 (can't be sender)
Websocket::broadcast()->to(1)->to(2)->emit('message', 'for your eyes only');
Websocket::broadcast()->to([1, 2])->emit('message', 'for your eyes only');

// join to subscribe the socket to a given channel
Websocket::join('some room');

// leave to unsubscribe the socket to a given channel
Websocket::leave('some room');*/