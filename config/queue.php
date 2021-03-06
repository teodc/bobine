<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */

    'default' => env('QUEUE_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'beanstalkd' => [
            'driver'      => 'beanstalkd',
            'host'        => env('BEANSTALKD_HOST', 'localhost'),
            'queue'       => env('QUEUE_NAME', 'default'),
            'retry_after' => env('BEANSTALKD_RETRY_AFTER', 90),
            'block_for'   => env('BEANSTALKD_BLOCK_FOR', 0),
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('AWS_SQS_PREFIX'), // e.g. https://sqs.us-east-1.amazonaws.com/your-account-id
            'queue'  => env('AWS_SQS_QUEUE'),
            'region' => env('AWS_DEFAULT_REGION'),
        ],

        'redis' => [
            'driver'      => 'redis',
            'connection'  => 'queue',
            'queue'       => env('QUEUE_NAME', 'default'),
            'retry_after' => 90,
            'block_for'   => null,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'driver'   => env('QUEUE_FAILED_DRIVER', 'database'),
        'database' => env('DB_CONNECTION'),
        'table'    => 'failed_jobs',
    ],

];
