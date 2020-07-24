<?php

namespace App\Http\Controllers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Illuminate\Session\SessionManager;

class Web_SocketController extends Controller implements MessageComponentInterface
{
    protected $clients;
    public function __construct()
    {
        $this->clients = [];
    }
    private function auth_user($conn)
    {
        $conn->session->start();
        $user_id = $conn->session->get(\Auth::getName());
        return !empty($user_id) ? \App\User::find($user_id) : null;
    }
    public function onOpen(ConnectionInterface $conn)
    {
        // Create a new session handler for this client
        $session = (new SessionManager(\App::getInstance()))->driver();
        // Get the cookies
        $cookies_header = $conn->httpRequest->getHeader('Cookie');
        $cookies = \GuzzleHttp\Psr7\parse_header($cookies_header)[0];
        // Get the laravel's one
        $cookie = urldecode($cookies[\Config::get('session.cookie')]);
        // get the user session id from it
        $session_id = \Crypt::decryptString($cookie);
        // Set the session id to the session handler
        $session->setId($session_id);
        // Bind the session handler to the client connection
        $conn->session = $session;
        // Get Auth user
        $user = $this->auth_user($conn);
        // echo "{$user->email} connected\n";
        // Store one connection per user
        $this->clients[$user->id] = $conn;
    }
    // more code ..
}
