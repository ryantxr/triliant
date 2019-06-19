<?php
namespace Triliant\Service;
use Triliant\Service\BaseService;
use Twilio\Rest\Client;
use Twilio\Values;
use Illuminate\Support\Facades\Log;

class PhoneNumber extends BaseService
{
    public function getInfo($number)
    {
        $phoneNumberInfo = $this->client->incomingPhoneNumbers
                                ->read([
                                    'phoneNumber' => $number
                                ], 1);
        if ( is_object($phoneNumberInfo[0]) ) {
            $phoneNumberInfo[0]->dateCreated = \Carbon\Carbon::instance($phoneNumberInfo[0]->dateCreated);
            $phoneNumberInfo[0]->dateUpdated = \Carbon\Carbon::instance($phoneNumberInfo[0]->dateUpdated);
        }
        // print($phoneNumberInfo->friendlyName);
        
        return $phoneNumberInfo[0] ?? false;
    }

    public function get($n)
    {
        $phoneNumberInfo = $this->client->incomingPhoneNumbers
                                ->read([], $n);
        return $phoneNumberInfo;

    }

    /**
     * "PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
     */
    public function release($sid)
    {
        try {
            $rval = $this->client->incomingPhoneNumbers($sid)->delete();
            if ( $rval ) {
                return ['status' => true, 'message' => 'phone number released'];
            }
            return ['status' => false, 'message' => 'phone number not released'];
        } catch(\Twilio\Exceptions\RestException $e) {
            Log::warning('Unable to delete phone from Twilio');
            Log::warning($e->getCode());
            Log::warning($e->getMessage());
            return  ['status' => false, 'message' => $e->getMessage()];
        }
        return false;
    }

    public function lookup($phone, $options=null)
    {
        if ( isset( $options['type'] ) ) {
            $lookupOptions['type'] = 'carrier';
        }
        $lookupOptions = [];
        $encodedNumber = rawurlencode($phone);
        try {
            $result = $this->client->lookups
                    ->phoneNumbers($encodedNumber)
                    ->fetch($lookupOptions);
            // $result->phoneNumber;
            // $number->carrier["type"];
            // $number->carrier["name"];
            Log::info("Lookup {$result->phoneNumber}");
            return ['status' => 1, 'phone' => $result->phoneNumber, 'message' => 'OK'];
        } catch ( \Twilio\Exceptions\RestException $e ) {
            Log::debug(get_class($e));
            Log::debug($e->getMessage());
            $trace = $e->getTrace();
            Log::debug('Exception: ' .__METHOD__. ' ' . $trace[2]['file']  .'('. $trace[2]['line'] . ')'  );
            // throw new Exception("Phone number lookup exception {$phone}");
            return ['status' => 0, 'phone' => null, 'message' => $e->getMessage()];
        }
    }
}