# Triliant - Twilio wrapper

Twilio APIs do a number of things beyond making calls and sending text messages.
For PHP, these APIs and corresponding SDK functions are not well documented.
Some of the SDK code it downright difficult to follow.
Many functions are poorly named.

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
