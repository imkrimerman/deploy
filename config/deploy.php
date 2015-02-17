<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Deploy Directory
    |--------------------------------------------------------------------------
    |
    | Specify directory where your projects are.
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
    | Specify what will be the deployment temporary storage.
    |
    */

    'storage' => storage_path('deploy'),

    /*
    |--------------------------------------------------------------------------
    | Deploy Fallback Branch
    |--------------------------------------------------------------------------
    |
    | This branch will be used if branch was not detected.
    |
    */

    'branch' => 'master'

];
