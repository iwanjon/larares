<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    "midtrans" => [
                    // Set your Merchant Server Key
            "serverKey" => env('SERVER_KEY',"xxxyyyzzz"),
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            "isProduction" => env('IS_PRODUCTION', false),
            // Set sanitization on (default)
            "isSanitized" => env('IS_SANITIZED',true),
            // Set 3DS transaction for credit card to true
            "is3ds" => env('IS_3DS',true),
            "midtransUrl" => env("MIDTRANS_URL")
    ],


];
