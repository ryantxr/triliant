<?php
namespace Triliant\Service;
use Triliant\Service\BaseService;
use Twilio\Rest\Client;
use Twilio\Values;

class MessageLog extends BaseService
{
    public function getMessage(string $sid)
    {
        $message = $this->client->messages($sid)->fetch();
        return $message;
    }

    public function get(array $criteria)
    {
        return $this->read($criteria);
    }

    public function read(?array $criteria)
    {
        $messages = $this->client->messages->read($criteria, 20);
        array_walk($messages, function($item){
            $item->dateSent = \Carbon\Carbon::instance($item->dateSent);
        });
        return $messages;

        // $messages = $twilio->messages->read(
        //     [
        //         // "dateSentBefore" => 
        //         // "dateSentAfter" => 
        //         // "dateSent" => new \DateTime('2016-8-31'),
        //         // "from" => "+15017122661",
        //         // "to" => "+15558675310"
        //     ],
        //     20
        //     );
        // foreach ($messages as $record) {
        //     print($record->sid);
        // }



        // foreach ($this->client->messages->read() as $message) {
        //     //echo $message->body . "\n";
        //     $data = [
        //         'from' => $message->from,
        //         'to' => $message->to,
        //         'direction' => $message->direction,
        //         'messagingServiceSid' => $message->messagingServiceSid,
        //         'numMedia' => $message->numMedia,
        //         'numSegments' => $message->numSegments,
        //         'price' => $message->price,
        //         'priceUnit' => $message->priceUnit,
        //         'sid' => $message->sid,
        //         'status' => $message->status,
        //         'body' => $message->body,
        //     ];
        // }
    }

    public function stream(?array $criteria)
    {
        $messages = $this->client->messages->stream($criteria);
        //     array( 
        //     'dateSentAfter' => '2015-05-01', 
        //     'dateSentBefore' => '2015-06-01'
        //     )
        //   );
        return $messages;
    }
}