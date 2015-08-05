<?php

/*
 * This file is part of GEKKO URL Shortener
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return array(

    /*
    |--------------------------------------------------------------------------
    | GEKKO: Domain Path
    |--------------------------------------------------------------------------
    | This is the path to where you want to find the GEKKO management panel
    | feel free to change this to what you want, but dont leave it blank and
    | dont include initial or trailing slash
    | [DEFAULT] => 'manage' (http://example.com/manage/)
    */

    'path' => 'manage',

    /*
    |--------------------------------------------------------------------------
    | GEKKO: Enable Registration
    |--------------------------------------------------------------------------
    | This allows users to register using the GEKKO management panel,
    | true => Allow users to register freely
    | false [DEFAULT] => Disable new user registration
    */

    'registration' => true,

    /*
    |--------------------------------------------------------------------------
    | GEKKO: Use HTTPS
    |--------------------------------------------------------------------------
    | Set if your application uses HTTPS, this allows GEKKO to use HTTPS URLs
    | instead, for more security.
    | true => Use HTTPS for all GEKKO related links
    | false [DEFAULT] => Disable HTTPS for all GEKKO related links
    */

    'https' => false,

);