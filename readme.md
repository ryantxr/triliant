# Triliant - Twilio wrapper

Twilio APIs do a number of things beyond making calls and sending text messages.
For PHP, these APIs and corresponding SDK functions are not well documented.
Some of the SDK code it downright difficult to follow.
Many functions are poorly named.

*This is an early dev version and isn't ready for general usage*

This library is meant to be a cleaner and better documented wrapper.

This library will support:

1. Get message logs.
2. Setup and manage a messaging service.
3. Lookup a phone number.
4. Add and delete phone numbers.
5. Sending SMS.

## Installation

Install this library using composer directly from the GIT repository.

Add this to composer.json.

    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:ryantxr/triliant.git"
        }
    ]

    "require" : [
        "ryantxr/triliant":"dev-master"
    ]

Then

    composer update

## Laravel Integration

Create a config file `config/sms.php` and put the following content into it.

    return [

        /*
        |--------------------------------------------------------------------------
        | Twilio configuration settings 
        | (see src/config/sms.php)
        |--------------------------------------------------------------------------
        |
        */

        'twilio' => [
            'default_from_number' => env('TWILIO_DEFAULT_FROM_NUMBER'),
            'default_from_sid' => env('TWILIO_DEFAULT_FROM_SID'),
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
        ]
    ];

Register the class.

Add the following to `app/Providers/AppServiceProvider.php`

    public function register()
    {
        $this->app->singleton(\Triliant\Client::class, function ($app) {
            return new \Triliant\Client(config('sms.twilio'));
        });
    }

Add entries to `.env`

    TWILIO_DEFAULT_FROM_NUMBER="+15550001111"
    TWILIO_DEFAULT_FROM_SID=PNxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    TWILIO_AUTH_TOKEN=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

Now you can use it like this:

    $triliant = resolve(\Triliant\Client::class);

    // Get the message log client to get message logs
    $msgLogClient = $triliant->messageLog();

    // Get phone number client to buy numbers
    $phoneNumberClient = $triliant->phoneNumber();

    // Get the sms client to send messages
    $smsClient = $triliant->message();

    // Get the messaging service client 
    $messagingServiceClient = $triliant->messagingService();

## Features

* Message logs
* SMS messaging
* Phone numbers
* Messaging service

### Message Logs

Get message logs

        $date = Carbon::parse('2019-04-21');
        $criteria['dateSent'] = $date;
        $messages = $msgClient->stream($criteria);
        foreach($messages as $message) {
            // dateSent is a DateTime. Convert to Carbon (if you want)
            $message->dateSent = Carbon::instance($message->dateSent);
            switch($directionString) {
                case 'inbound':
                    $direction = 'received';
                    break;
                case 'outbound-api':
                    $direction = 'sent';
                    break;
                default:
                    $direction = 0;
                    break;
            }
            // Was this sent from a toll-free number?
            $isTollFree = in_array(substr($message->from, 0, 5), ['+1800','+1833','+1844','+1855','+1866','+1877','+1888']);
            $message->sid;
            $message->dateSent;
            $message->status;
            $message->from;
            $message->to;
            $message->errorCode;
            $message->errorMessage;
            $message->messagingServiceSid;
            $message->body;
            $message->price;
            $message->priceUnit;
            $message->numMedia;
            $message->numSegments;
            $direction;
            $isTollFree;
        }
