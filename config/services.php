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

  'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    'scheme' => 'https',
  ],

  'postmark' => [
    'token' => env('POSTMARK_TOKEN'),
  ],

  'ses' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
  ],

  'tripay' => [
    'key' => env('TRIPAY_API_KEY'),
    'secret' => env('TRIPAY_SECRET_KEY'),
    'merchant' => env('TRIPAY_MERCHANT_CODE')
  ],

  'platform' => [
    'fee' => env('PLATFORM_FEE', 1)
  ],

  'crypto' => [
    'bsc' => [
      'api_key' => env('BSCSCAN_API_KEY', null)
    ],
    'eth' => [
      'api_key' => env('ETHERSCAN_API_KEY', null)
    ],
    'sol' => [
      'api_key' => env('SOLSCAN_API_KEY', null)
    ],
    'idr' => [
      'api_key' => env('IDR_API_KEY', null)
    ],
    'metamask' => [
      'private_key' => env('METAMASK_PRIVATE_KEY', null),
      'eth_address' => env('METAMAS_ETH_ADDRESS', null)
    ],
  ],

  'digiflazz' => [
    'username' => env('DIGIFLAZZ_USERNAME', null),
    'api_key' => env('DIGIFLAZZ_API_KEY', null),
    'platform_key' => env('DIGIFLAZZ_PLATFORM_SECRET_KEY', null),
  ],

];
