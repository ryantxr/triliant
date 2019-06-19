<?php
namespace Triliant\Service;
use Triliant\Service\BaseService;
use Twilio\Rest\Client;
use Twilio\Values;
use Carbon\Carbon;

/*
properties of a messaging service
inboundRequestUrl
    The URL Twilio will make a webhook request to when a message is 
    received by any phone number or short code in your Service. 
    When this property is null receiving inbound messages is 
    disabled. All messages sent to your Twilio phone number 
    or short code will not be logged and received on your 
    Account.
inboundMethod
    The HTTP method Twilio will use when making requests to the Inbound Request URL. 
    Possible values are GET or POST.
fallbackUrl
    The URL that Twilio will request if an error occurs when retrieving or executing 
    the TwiML from your Inbound Request URL.
fallbackMethod
    The HTTP method Twilio will use when making requests to the Fallback URL. 
    Possible values include GET or POST.
statusCallback
    The URL Twilio will make a webhook request to when passing you status 
    updates about the delivery of your messages.
stickySender
    Configuration to enable or disable Sticky Sender on your Service instance. 
    Possible values are true and false.
mmsConverter
    Configuration to enable or disable MMS Converter for messages sent through your 
    Service instance. Possible values are true and false.
smartEncoding
    Configuration to enable or disable Smart Encoding for messages sent through your 
    Service instance. Possible values are true and false.
scanMessageContent
fallbackToLongCode
    Configuration to enable or disable Fallback to Long Code for messages sent through 
    your Service instance. Possible values are true and false.
areaCodeGeomatch
    Configuration to enable or disable Area Code Geomatch on your Service Instance. 
    Possible values are true and false.
synchronousValidation
validityPeriod
    The number of seconds all messages sent from your Service are valid for. 
    Acceptable integers range from 1 to 14,400.
url
links


*/
class MessagingService extends BaseService
{
    public function get($sid)
    {
        $service = $this->client->messaging->v1->services($sid)->fetch();
        if ( $service ) {
            $service->dateCreated = Carbon::instance($service->dateCreated);
        }
        return $service;
    }
    
    /**
     * Gets a list of all messaging services
     */
    public function getList()
    {
        $services = $this->client->messaging->v1->services->read([], 20);
        return $services;
    }

    /**
     * Get the numbers for a messaging service.
     */
    public function numbers(string $messagingservice, $pageSize=null, $pageNum=0)
    {
        if ( ! $this->client ) {
            return false;
        }
        if ( ! $messagingservice ) {
            // $this->out("No Messaging Service. Specify --messagingservice MGxxxxxxxx");
            return false;
        }
        // $this->out("Counting phone numbers in Messaging Service " . $this->param('messagingservice') );

        if ( $pageSize !== null ) {
            if ( $pageNum ) {
                $phoneNumbers = $this->client->messaging->v1->services($messagingservice)->phoneNumbers->read($pageSize, $pageNum);
            } else {
                $phoneNumbers = $this->client->messaging->v1->services($messagingservice)->phoneNumbers->read($pageSize);
            }
        } else {
            $phoneNumbers = $this->client->messaging->v1->services($messagingservice)->phoneNumbers->read();
        }
        // return $phoneNumbers;

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $phones = [];
        foreach ($phoneNumbers as $phoneNumber) {
            // $this->out($phoneNumber->phoneNumber);
            try {
                $numberProto = $phoneUtil->parse($phoneNumber->phoneNumber, "US");
                $formattedNumber = $phoneUtil->format($numberProto, \libphonenumber\PhoneNumberFormat::NATIONAL);
            } catch (\libphonenumber\NumberParseException $e) {
                $formattedNumber = null;
            }
            $phones[] = (object)[
                'sid' => $phoneNumber->sid,
                'dateCreated' => Carbon::make($phoneNumber->dateCreated),
                'phoneNumber' => $phoneNumber->phoneNumber,
                'friendlyName' => $formattedNumber,
                'countryCode' => $phoneNumber->countryCode,
                'capabilities' => $phoneNumber->capabilities
            ];
        }
        // $this->out(count($phones) . " numbers");
        // dd($phones);
        return $phones;
    }

    /**
     * Buy numbers for a messaging service
     */
    public function buyNumbers(string $messagingservice, int $count)
    {
        $availableNumbers = $this->client->availablePhoneNumbers('US')->tollFree->read();
        $numbers = [];
        $numbersBought = 0;
        foreach($availableNumbers as $number) {
            if ( $numbersBought < $count ) {
                // $this->out(sprintf("Buying %s", $number->phoneNumber));
                $numbers[] = $number->phoneNumber;
                $boughtNumber = $this->client->incomingPhoneNumbers
                    ->create(
                    [
                        "phoneNumber" => $number->phoneNumber
                    ]
                    );
                // Log::debug(sprintf('Bought %s %s', $number->phoneNumber, $boughtNumber->sid));
                // Log::debug(sprintf('Attaching phone %s to service %s', $number->phoneNumber, $this->param('messagingservice')));
                $phoneNumber = $this->client->messaging->v1->services($messagingservice)
                    ->phoneNumbers->create($boughtNumber->sid);
            } else {
                // $this->line(sprintf("Number %s", $number->phoneNumber));
            }
            $numbersBought++;
        }
        return $numbers;
    }

    /**
     * Removes a phone number from a messaging service
     */
    public function removeNumber(string $messagingservice,  string $numbersid)
    {
        $result = $this->client->messaging->v1->services($messagingservice)
            ->phoneNumbers($numbersid)
            ->delete();
        return $result;
    }
}