<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            
        ],
        'backups' => [
            'driver' => 'local', // កំណត់ driver ជា 'local'
            'root' => storage_path('app/backups'), //ទីតាំង 
        ],
        // 'google' => [
        //     'driver'        => 'google',
        //     'clientId'      => env('GOOGLE_DRIVE_CLIENT_ID'),
        //     'clientSecret'  => env('GOOGLE_DRIVE_CLIENT_SECRET'),
        //     'refreshToken'  => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
        //     'folderId'      => env('GOOGLE_DRIVE_FOLDER_ID'),
        //     'cache'       => false, // <-- បន្ថែមកូដនេះដើម្បីបិទ Caching
        // ],

        'google' => [
            'driver' => 'google',
            'folderId' => env('1y4Zl5UuRKA1vNKIcxXnowQzilfNzk2RK'), // ដាក់ Folder ID ដែលអ្នកបានចម្លង
            'service_account_credentials_json' => storage_path('app/secrets/service-account.json'), // ទីតាំងរបស់ Key File
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
