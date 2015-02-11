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

    'directory' => realpath(base_path().'/../'),

    /*
    |--------------------------------------------------------------------------
    | Deploy File Name
    |--------------------------------------------------------------------------
    |
    | Specify what will be the name of deployment configuration file.
    |
    */

    'file' => '.deploy.yml'

];
