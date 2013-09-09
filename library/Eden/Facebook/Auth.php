<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Facebook;

use Eden\Utility\Oauth2\Client;
use Eden\Utility\Argument;

/**
 * Facebook Authentication
 *
 * @vendor  Eden
 * @package Eden\Facebook
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Auth extends Client
{
    const APPLICATION_URL = 'https://graph.facebook.com/oauth/access_token?client_id=%s&client_secret=%s&grant_type=%s';
    const CREDENTIALS = 'client_credentials';
    const REQUEST_URL = 'https://www.facebook.com/dialog/oauth';
    const ACCESS_URL = 'https://graph.facebook.com/oauth/access_token';
    const USER_AGENT = 'facebook-php-3.1';
	
    protected $appId = null;
    protected $secret = null;
    protected $redirect = null;

    /**
     * Returns the oauth 2 client class
     * 
     * @param string $key
     * @param string $secret
     * @param url $redirect
     */
    public function __construct($key, $secret, $redirect)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'url'); // argument 3 must be a url

        $this->appId = $key;
        $this->secret = $secret;
        $this->redirect = $redirect;

        parent::__construct($key, $secret, $redirect, self::REQUEST_URL, self::ACCESS_URL);
    }

    public function getAppToken()
    {
        //request a application access token
        $tokenUrl = sprintf(self::APPLICATION_URL, $this->appId, $this->secret, self::CREDENTIALS);
        $appToken = file_get_contents($tokenUrl);
        //convert the query to array
        parse_str($appToken, $token);
        //check access token is already set
        if (!isset($token['access_token'])) {
            //return if theres an error
            return $token;
        } else {
            //get the access token
            return $token['access_token'];
        }
    }
}