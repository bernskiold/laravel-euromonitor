<?php

return [

    /**
     * API Connection Details for the Euromonitor API
     */
    'api' => [

        /**
         * Your username for the Euromonitor API Portal
         *
         * This is the username you use to log in to the Euromonitor API Portal.
         *  We recommend setting up a specific shared user for this purpose.
         */
        'username' => env('EUROMONITOR_USERNAME'),

        /**
         * Your password for the Euromonitor API Portal
         *
         * This is the password you use to log in to the Euromonitor API Portal.
         * We recommend setting up a specific shared user for this purpose.
         */
        'password' => env('EUROMONITOR_PASSWORD'),

        /**
         * Your primary API key for the Euromonitor API Portal
         * for the user given above.
         *
         * You can find this key in your user profile in the Euromonitor API Portal.
         * It is the "Primary API Key".
         */
        'api_key' => env('EUROMONITOR_API_KEY'),

        /**
         * The base URL for the Euromonitor API
         */
        'base_url' => env('EUROMONITOR_BASE_URL', 'https://api.euromonitor.com'),

        /**
         * The version of the API to use.
         *
         * Currently, the only supported version is 1.0.
         * This setting is here for future compatibility.
         */
        'version' => env('EUROMONITOR_API_VERSION', '1.0'),
    ],

    'cache' => [

        /**
         * The cache prefix is used to avoid key collisions.
         * All keys will be nested under this prefix
         * (e.g. "euromonitor.access_token").
         */
        'prefix' => 'euromonitor',

    ],

    /**
     * The Euromonitor API has a strict fair use limit.
     * To download bigger datasets you can use the mass extraction
     * feature which will allow you to download big data queries
     * in the background in bulk.
     *
     * This requires the use of queues.
     */
    'mass_extraction' => [

        /**
         * The queue to use for mass extraction jobs.
         *
         * This is the queue where the mass extraction jobs will be dispatched.
         * You can use any queue defined in your queue.php config.
         *
         * The default is the "default" queue.
         */
        'queue' => 'default',

        /**
         * How long to wait before attempting to
         * check the status again for a mass extraction job.
         *
         * This is in seconds.
         */
        'wait_time' => 60 * 2, // 2 minutes

        /**
         * The filesystem to use for storing the downloaded files.
         * This can be any filesystem defined in your filesystems.php config.
         *
         * The default is the local filesystem.
         */
        'disk' => env('EUROMONITOR_DISK', 'local'),

        /**
         * The folder to store the downloaded files in.
         *
         * This is a subfolder in the disk where the files will be stored.
         */
        'folder' => 'euromonitor',

    ],

];
