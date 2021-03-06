<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2013-2014 Openovate Labs
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
     * @param string $key      the application's key
     * @param string $secret   the application's secret
     * @param string $redirect the application's redirect uri
     * @return void
     */
    public function __construct($key, $secret, $redirect)
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string')
                ->test(3, 'url');

        parent::__construct($key, $secret, $redirect, self::REQUEST_URL, self::ACCESS_URL);
    }
}
