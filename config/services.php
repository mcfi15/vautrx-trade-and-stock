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

    /*
    |--------------------------------------------------------------------------
    | CoinGecko API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for CoinGecko API integration to fetch cryptocurrency
    | prices, market data, and other information.
    |
    */

    'coingecko' => [
        'base_url' => env('COINGECKO_API_URL', 'https://api.coingecko.com/api/v3'),
        'api_key' => env('COINGECKO_API_KEY', ''), // Optional: Pro API key
        'timeout' => env('COINGECKO_TIMEOUT', 30),
        'cache_ttl' => env('COINGECKO_CACHE_TTL', 60), // Cache duration in seconds
        'rate_limit' => [
            'enabled' => true,
            'max_requests_per_minute' => env('COINGECKO_RATE_LIMIT', 50),
        ],
        'currency' => env('COINGECKO_CURRENCY', 'usd'),
        'endpoints' => [
            'ping' => '/ping',
            'simple_price' => '/simple/price',
            'coins_list' => '/coins/list',
            'coins_markets' => '/coins/markets',
            'coin_details' => '/coins/{id}',
            'coin_market_chart' => '/coins/{id}/market_chart',
            'coin_ohlc' => '/coins/{id}/ohlc',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Binance API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Binance API integration (optional alternative
    | to CoinGecko for real-time trading data).
    |
    */

    'binance' => [
        'enabled' => env('BINANCE_ENABLED', false),
        'api_key' => env('BINANCE_API_KEY', ''),
        'api_secret' => env('BINANCE_API_SECRET', ''),
        'base_url' => env('BINANCE_API_URL', 'https://api.binance.com/api/v3'),
        'testnet' => env('BINANCE_TESTNET', false),
        'testnet_url' => 'https://testnet.binance.vision/api/v3',
        'timeout' => env('BINANCE_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | CoinMarketCap API Configuration
    |--------------------------------------------------------------------------
    |
    | Alternative cryptocurrency data provider.
    |
    */

    'coinmarketcap' => [
        'enabled' => env('COINMARKETCAP_ENABLED', false),
        'api_key' => env('COINMARKETCAP_API_KEY', ''),
        'base_url' => env('COINMARKETCAP_API_URL', 'https://pro-api.coinmarketcap.com/v1'),
        'timeout' => env('COINMARKETCAP_TIMEOUT', 30),
        'cache_ttl' => env('COINMARKETCAP_CACHE_TTL', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Notification Services
    |--------------------------------------------------------------------------
    |
    | Configuration for various email notification scenarios.
    |
    */

    'notifications' => [
        'email' => [
            'enabled' => env('NOTIFICATIONS_EMAIL_ENABLED', true),
            'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@crypto-platform.com'),
            'from_name' => env('MAIL_FROM_NAME', 'Crypto Trading Platform'),
        ],
        'sms' => [
            'enabled' => env('NOTIFICATIONS_SMS_ENABLED', false),
            'provider' => env('SMS_PROVIDER', 'twilio'), // twilio, nexmo, etc.
        ],
        'push' => [
            'enabled' => env('NOTIFICATIONS_PUSH_ENABLED', false),
            'provider' => env('PUSH_PROVIDER', 'pusher'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Twilio SMS Service
    |--------------------------------------------------------------------------
    |
    | Configuration for Twilio SMS notifications.
    |
    */

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'from_number' => env('TWILIO_FROM_NUMBER'),
        'verify_sid' => env('TWILIO_VERIFY_SID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pusher Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Pusher real-time notifications.
    |
    */

    'pusher' => [
        'app_id' => env('PUSHER_APP_ID'),
        'app_key' => env('PUSHER_APP_KEY'),
        'app_secret' => env('PUSHER_APP_SECRET'),
        'app_cluster' => env('PUSHER_APP_CLUSTER', 'mt1'),
        'host' => env('PUSHER_HOST'),
        'port' => env('PUSHER_PORT', 443),
        'scheme' => env('PUSHER_SCHEME', 'https'),
        'encrypted' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Redis cache and queue management.
    |
    */

    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', 'crypto_platform_'),
        ],
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],
        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Services
    |--------------------------------------------------------------------------
    |
    | Configuration for Google services (OAuth, reCAPTCHA, etc.).
    |
    */

    'google' => [
        'recaptcha' => [
            'enabled' => env('GOOGLE_RECAPTCHA_ENABLED', false),
            'site_key' => env('GOOGLE_RECAPTCHA_SITE_KEY'),
            'secret_key' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
            'version' => env('GOOGLE_RECAPTCHA_VERSION', 'v2'),
        ],
        'oauth' => [
            'enabled' => env('GOOGLE_OAUTH_ENABLED', false),
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect' => env('GOOGLE_REDIRECT_URI'),
        ],
        // Socialite configuration (will be overridden by database settings)
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Analytics Services
    |--------------------------------------------------------------------------
    |
    | Configuration for analytics and tracking services.
    |
    */

    'analytics' => [
        'google_analytics' => [
            'enabled' => env('GOOGLE_ANALYTICS_ENABLED', false),
            'tracking_id' => env('GOOGLE_ANALYTICS_ID'),
        ],
        'mixpanel' => [
            'enabled' => env('MIXPANEL_ENABLED', false),
            'token' => env('MIXPANEL_TOKEN'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Services
    |--------------------------------------------------------------------------
    |
    | Configuration for payment gateway integrations (for fiat deposits).
    |
    */

    'payment_gateways' => [
        'stripe' => [
            'enabled' => env('STRIPE_ENABLED', false),
            'public_key' => env('STRIPE_PUBLIC_KEY'),
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
        'paypal' => [
            'enabled' => env('PAYPAL_ENABLED', false),
            'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | KYC/AML Services
    |--------------------------------------------------------------------------
    |
    | Configuration for Know Your Customer verification services.
    |
    */

    'kyc' => [
        'enabled' => env('KYC_ENABLED', false),
        'provider' => env('KYC_PROVIDER', 'sumsub'), // sumsub, onfido, jumio
        'sumsub' => [
            'app_token' => env('SUMSUB_APP_TOKEN'),
            'secret_key' => env('SUMSUB_SECRET_KEY'),
            'base_url' => env('SUMSUB_BASE_URL', 'https://api.sumsub.com'),
        ],
        'onfido' => [
            'api_token' => env('ONFIDO_API_TOKEN'),
            'base_url' => env('ONFIDO_BASE_URL', 'https://api.onfido.com/v3'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging & Monitoring Services
    |--------------------------------------------------------------------------
    |
    | Configuration for external logging and monitoring services.
    |
    */

    'logging' => [
        'sentry' => [
            'enabled' => env('SENTRY_ENABLED', false),
            'dsn' => env('SENTRY_LARAVEL_DSN'),
            'environment' => env('APP_ENV', 'production'),
        ],
        'loggly' => [
            'enabled' => env('LOGGLY_ENABLED', false),
            'token' => env('LOGGLY_TOKEN'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Global rate limiting configuration for API endpoints.
    |
    */

    'rate_limiting' => [
        'enabled' => env('RATE_LIMITING_ENABLED', true),
        'driver' => env('RATE_LIMITING_DRIVER', 'redis'), // redis, database, cache
        'limits' => [
            'api' => env('RATE_LIMIT_API', 60), // requests per minute
            'trading' => env('RATE_LIMIT_TRADING', 120),
            'withdrawal' => env('RATE_LIMIT_WITHDRAWAL', 10),
        ],
    ],

    'market_data' => [
        'provider' => env('MARKET_DATA_PROVIDER', 'finnhub'),
        'key' => env('MARKET_DATA_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Financial Market Data Services
    |--------------------------------------------------------------------------
    |
    | Configuration for financial market data providers
    |
    */

    'fmp' => [
        'api_key' => env('FMP_API_KEY', 'demo'),
        'base_url' => env('FMP_BASE_URL', 'https://financialmodelingprep.com/api/v3'),
    ],

    'alpha_vantage' => [
        'api_key' => env('ALPHA_VANTAGE_API_KEY'),
        'base_url' => env('ALPHA_VANTAGE_BASE_URL', 'https://www.alphavantage.co/query'),
    ],

    'yahoo_finance' => [
        'base_url' => env('YAHOO_FINANCE_BASE_URL', 'https://query1.finance.yahoo.com/v8/finance'),
    ],

];
