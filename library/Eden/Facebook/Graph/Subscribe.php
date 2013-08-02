<?php

//-->	
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.st.
 */

namespace Eden\Facebook\Graph;

use Eden\Facebook\Base;
use Eden\Utility\Curl;

/**
 * Facebook Subscribe
 *
 * @vendor  Eden
 * @package Eden\Facebook\Graph
 * @author  Christian Symon M. Buenavista <sbuenavista@openovate.com>
 * @since   1.0.0
 */
class Subscribe extends Base
{
    const SUBSCRIBE_URL = 'https://graph.facebook.com/%s/subscriptions';
    const APPLICATION_URL = 'https://graph.facebook.com/oauth/access_token?client_id=%s&client_secret=%s&grant_type=%s';
    const CREDENTIALS = 'client_credentials';
    protected $appId;
    protected $token = null;
    protected $meta = null;

    /**
     * Initialize the default value of authentication
     *
     * @param string|array
     * @return void
     */
    public function __construct($clientId, $secret)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string'); // argument 2 must be a string

        $this->appId = $clientId;

        //request a application access token
        $tokenUrl = sprintf(self::APPLICATION_URL, $clientId, $secret, self::CREDENTIALS);
        $appToken = file_get_contents($tokenUrl);
        //convert the query to array
        parse_str($appToken, $token);
        //check access token is already set
        if (!isset($token['access_token'])) {
            //return if theres an error
            return $token;
        } else {
            //get the access token
            $this->token = $token['access_token'];
        }
    }

    /**
     * Returns the meta of the last call
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Returns each of users subscribed objects and their subscribed fields 
     *
     * @return array
     */
    public function getSubscription()
    {
        return $this->getResponse(sprintf(self::SUBSCRIBE_URL, $this->appId));
    }

    /**
     * Subscribes to Facebook real-time updates
     * 
     * @param string Type of Facebook object (user, permissions, page)
     * @param string Comma-deliminated list of fields to subscribe to (e.g. "name,picture,friends,feed")
     * @param url Callback-url for the real-time updates
     */
    public function subscribe($object, $fields, $callbackUrl)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'url'); // argument 3 must be a url
        //populate fields
        $query = array(
            'object' => $object,
            'fields' => $fields,
            'callback_url' => $callbackUrl,
            'verify_token' => sha1($this->appId . $object . $callbackUrl));

        //generate url
        $token = array('access_token' => $this->token);
        $url = sprintf(self::SUBSCRIBE_URL, $this->appId) . '?' . http_build_query($token);

        var_dump($url);
        return $this->post($url, $query);
    }

    /**
     * Send a post action to the facebook
     * 
     * @param type $url
     * @param array $query
     * @return type
     */
    protected function post($url, array $query = array())
    {
        //set curl
        $curl = Curl::i()
                ->setConnectTimeout(10)
                ->setFollowLocation(true)
                ->setTimeout(60)
                ->verifyHost(false)
                ->verifyPeer(false)
                ->setUrl($url)
                ->setPost(true)
                ->setPostFields($query)
                ->setHeaders('Expect');
        //get response form curl
        $response = $curl->getJsonResponse();
        //get curl infomation
        $this->meta = $curl->getMeta();
        $this->meta['url'] = $url;
        $this->meta['query'] = $query;
        $this->meta['response'] = $response;

        return $response;
    }

    /**
     * Get the response from the facebook
     * 
     * @param string $url
     * @param array $query
     * @return type
     */
    protected function getResponse($url, array $query = array())
    {
        //if needed, add access token to query
        $query['access_token'] = $this->token;
        //build url query
        $url = $url . '?' . http_build_query($query);
        //set curl
        $curl = Curl::i()
                ->setUrl($url)
                ->verifyHost(false)
                ->verifyPeer(false)
                ->setTimeout(60);
        //get response from curl
        $response = $curl->getJsonResponse();
        //get curl infomation
        $this->meta['url'] = $url;
        $this->meta['query'] = $query;
        $this->meta['curl'] = $curl->getMeta();
        $this->meta['response'] = $response;

        return $response;
    }
}