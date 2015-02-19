<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Deploy Directory
    |--------------------------------------------------------------------------
    |
    | Specify directory where to handle projects.
    |
    */

    'directory' => realpath(base_path('../')),

    /*
    |--------------------------------------------------------------------------
    | Deploy File Name
    |--------------------------------------------------------------------------
    |
    | Specify what will be the name of deployment configuration file.
    |
    */

    'filename' => '.deploy.yml',

    /*
    |--------------------------------------------------------------------------
    | Deploy Temporary Storage
    |--------------------------------------------------------------------------
    |
    | Path to deployment temporary storage. Storage is used to keep temporary
    | cloned project.
    |
    */

    'storage' => storage_path('deploy'),

    /*
    |--------------------------------------------------------------------------
    | Deploy Branch
    |--------------------------------------------------------------------------
    |
    | Deploy branch is used for retrieving deployment configuration file.
    | This branch will be used if branch was not detected.
    |
    */

    'branch' => 'master',

    /*
    |--------------------------------------------------------------------------
    | Deploy Mail Notification.
    |--------------------------------------------------------------------------
    |
    | After each deployment we will send result to this emails.
    |
    */

    'notify' => [
        'i.m.krimerman@gmail.com'
    ],

    /*
    |--------------------------------------------------------------------------
    | Deploy Log File Path
    |--------------------------------------------------------------------------
    |
    | Path to log file. You can specify where deploy log will be placed.
    |
    */

    'log' => storage_path('deploy/deploy.log'),

];
