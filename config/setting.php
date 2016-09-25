<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    | This option allows you to configure the settings model.
    |
    */

    'model' => Larapacks\Setting\Models\Setting::class,

    /*
    |--------------------------------------------------------------------------
    | Encryption
    |--------------------------------------------------------------------------
    |
    | This option allows you to enable / disable encryption.
    |
    | If enabled, **all** setting values are encrypted using
    | your configured applications cipher and key.
    |
    | If you disable encryption after use, you will receive the raw encrypted
    | string for settings that have previously been encrypted.
    |
    */

    'encryption' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | This option allows you to configure the cache time in minutes.
    |
    */

    'cache' => 60,

];
