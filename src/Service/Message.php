<?php
namespace Triliant\Service;
use Triliant\Service\BaseService;
use Twilio\Rest\Client;
use Twilio\Values;
use Carbon\Carbon;

class Message extends BaseService
{
    /**
     * Send SMS or MMS
     * 
     */
    public function send(string $to, string $message, $from=null, $imageUrl=null)
    {
        $from = $from ?? $this->fromNumber;
        //'+1' . Configure::read('twilio.defaultFromNumber');
        $options = ["from" => $from, "body" => $message];
        if ( $imageUrl ) {
            $options['mediaUrl'] = $imageUrl;
        }
        $response = $this->client->messages->create($to, $options);
        return ['sid' => $response->sid, 'status' => $response->status, 'dateSent' => Carbon::instance($response->dateSent),
            'errorCode' => $response->errorCode, 'errorMessage' => $response->errorMessage,
            'raw' => $response];
    }
}