<?php

//-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Facebook;

use Eden\Oauth\Oauth2\Client;

/**
 * Facebook Authentication
 *
 * @vendor Eden
 * @package Facebook
 * @author Ian Mark Muninio <ianmuninio@openovate.com>
 */
class Auth extends Client
{
    const INSTANCE = 0; // set to multiton
    const REQUEST_URL = 'https://www.facebook.com/dialog/oauth';
    const ACCESS_URL = 'https://graph.facebook.com/oauth/access_token';
    const CREDENTIALS = 'client_credentials';
    const USER_AGENT = 'facebook-php-3.1';

    /**
     * Sets the application's key, secret and redirect uri.
     * 
     * @param number $key the application's key
     * @param alphanum $secret the application's secret
     * @param url $redirect the application's redirect uri
     */
    public function __construct($key, $secret, $redirect)
    {
        Argument::i()
                ->test(1, 'number') // argument 1 must be a number
                ->test(2, 'alphanum') // argument 2 must be a alphanum
                ->test(3, 'url'); // argument 3 must be a url

        parent::__construct($key, $secret, $redirect, self::REQUEST_URL, self::ACCESS_URL);
    }

}