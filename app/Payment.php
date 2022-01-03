<?php
namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Payment implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {

        echo "New connection! ({$conn->resourceId})\n";
        
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $this->parseMessage($from,$msg);
        
    }

    public function onClose(ConnectionInterface $conn) {
        echo "Disconnect! ({$conn->resourceId})\n";

    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }


    protected function parseMessage($from,$msg) {
        $req = json_decode($msg);
        echo($msg . "\r\n");

        switch($req->command) {
            case 'getVersion':
            case 'getPaymentMethods':
            case 'setup':
            case 'status':           
            case 'startPayment':
                    $c = $req->command;
                    $this->$c($from,$req);
                break;
            default:
                !d('error, not found ' . $req->command);



        }

    }
    protected function startPayment($from,$req){
        !d($req);


    }
    protected function status($from,$req) {
        $ret = json_encode (
            [ 
                'command'           => 'status',
                'result'            => 'ok',
                'paymentMethods'    => [
                    [
                        'method'        => 'bankCard',
                        "deviceStatus"  => "inService",
                        "deviceStatusMessage" => "ready",
                        "status" => "inService",
                        "statusMessage" => "ready",


                    ]



                ]
            
            ]
        );
        $from->send($ret);



    }

    protected function setup($from,$req) {
        $ret = json_encode (
            [ 
                'command'           => 'setup',
                'result'            => 'ok',
            
            ]
        );
        $from->send($ret);



    }

    protected function getVersion($from, $msg){
        $ret = json_encode (
            [ 
                'command'           => 'getVersion',
                'result'            => 'ok',
                'softwareVersion'   => '0.9.0',
                'protocolVersion'   => "1",
            
            ]
        );
        $from->send($ret);


    }

    protected function getPaymentMethods($from, $msg){
        $ret = json_encode (
            [ 
                'command'           => 'getPaymentMethods',
                'result'            => 'ok',
                'paymentMethods'    => [
                        [
                            "method"    => 'bankCard',
                            "capabilities" => [ "partialRefund" => true]
                        ],

                ]
            
            ]
        );
        $from->send($ret);
    }    
}