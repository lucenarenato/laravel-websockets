<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Http\Controllers\Controller;
use App\MarketPlace;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\UserSearchTerm;
use AWS;
use Aws\TranscribeService\TranscribeServiceClient;
use Aws\Acm\AcmClient;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SearchController extends Controller
{
    public function transcribeVoice()
    {
        // $data ='arn:aws:sns:eu-central-1:869076451876:app/GCM/AINDA-STG';
        // $protocol = "ssl";
        // $host = "arn:aws:sns:eu-central-1:869076451876:app/GCM/AINDA-STG";
        // $port = 443;
        // $path = "AINDA-STG";
        // $timeout = 2000;

        // $socket = @fsockopen($protocol . "://" . $host, $port,
        //             $errno, $errstr, $timeout);

        // if($socket === false) { return false; };

        // $content = "{'action': 'sendmessage', 'data': 'test'}";
        // $body  = "POST $path HTTP/1.1\r\n";
        // $body .= "Host: $host\r\n";
        // $body .= "Referer: yourClass (v.".version() .")\r\n";
        // $body .= "Content-type: application/json\r\n";
        // $body .= "Content-Length: ".strlen($content)."\r\n";
        // $body .= "Connection: Close\r\n\r\n";
        // $body .= "$content";
        // fwrite($socket, $body);
        // fclose($socket);
        


    }
}