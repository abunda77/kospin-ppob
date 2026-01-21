<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Registered IP Address
    |--------------------------------------------------------------------------
    |
    | This value is the registered/whitelisted IP address that is allowed
    | to access the application. This is used for network connection
    | verification and security purposes.
    |
    */

    'registered_ip' => env('REGISTERED_IP'),

    /*
    |--------------------------------------------------------------------------
    | Proxy Port
    |--------------------------------------------------------------------------
    |
    | This value is the port number used for the SSH tunnel/proxy connection.
    | This is typically set to 1080 for SOCKS proxy. The port checker will
    | verify if this port is open to ensure the SSH tunnel is running.
    |
    */

    'proxy_port' => (int) env('PROXY_PORT', 1080),

    /*
    |--------------------------------------------------------------------------
    | VPS Configuration for SSH Tunnel
    |--------------------------------------------------------------------------
    |
    | These values are used for SSH tunnel connection to VPS.
    | VPS IP: The IP address of your Virtual Private Server
    | VPS User: The username for SSH connection to VPS
    |
    */

    'vps_ip' => env('VPS_IP'),
    'vps_user' => env('VPS_USER'),

    /*
    |--------------------------------------------------------------------------
    | Export Limits
    |--------------------------------------------------------------------------
    |
    | These values determine the maximum number of records that can be
    | exported for different formats. This helps prevent memory issues
    | and timeout errors when dealing with large datasets.
    |
    */

    'export_limits' => [
        'pdf_max_records' => env('EXPORT_PDF_MAX_RECORDS', 1000),
        'excel_chunk_size' => env('EXPORT_EXCEL_CHUNK_SIZE', 1000),
    ],

];
