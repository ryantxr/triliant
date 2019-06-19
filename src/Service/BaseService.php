<?php
namespace Triliant\Service;
use Twilio\Rest\Client as TwilioRestClient;
use Twilio\Values;

class BaseService
{
    protected $client;
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
        $this->client = new TwilioRestClient($this->accountSid, $this->authToken);
    }
}