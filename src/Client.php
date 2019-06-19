<?php
namespace Triliant;

class Client
{
    protected $fromNumber;
    protected $fromSid;
    protected $accountSid;
    protected $authToken;

    public function __construct($config)
    {
        $this->fromNumber = $config['default_from_number'];
        $this->fromSid = $config['default_from_sid'];
        $this->accountSid = $config['account_sid'];
        $this->authToken = $config['auth_token'];
    }
    
    protected function config()
    {
        return [
            'default_from_number' => $this->fromNumber,
            'default_from_sid' => $this->fromSid,
            'account_sid' => $this->accountSid,
            'auth_token' => $this->authToken,
        ];
    }

    /**
     * Deals with the SMS message log
     */
    public function message()
    {
        if ( isset($this->message) && is_object($this->message) ) {
            return $this->message;
        }
        $this->message = new \Triliant\Service\Message($this->config());
        return $this->message;
    }

    /**
     * Deals with the SMS message log
     */
    public function messageLog()
    {
        if ( isset($this->messageLog) && is_object($this->messageLog) ) {
            return $this->messageLog;
        }
        $this->messageLog = new \Triliant\Service\MessageLog($this->config());
        return $this->messageLog;
    }

    /**
     * Deals with phone numbers. Buying, look up etc.
     */
    public function phoneNumber()
    {
        if ( isset($this->phoneNumber) && is_object($this->phoneNumber) ) {
            return $this->phoneNumber;
        }
        $this->phoneNumber = new \Triliant\Service\PhoneNumber($this->config());
        return $this->phoneNumber;
    }

    /**
     * Deals with messaging service
     */
    public function messagingService()
    {
        if ( isset($this->messagingService) && is_object($this->messagingService) ) {
            return $this->messagingService;
        }
        $this->messagingService = new \Triliant\Service\MessagingService($this->config());
        return $this->messagingService;
    }


}