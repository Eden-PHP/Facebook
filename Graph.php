<?php

//-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.st.
 */

namespace Eden\Facebook;

use Eden\Curl\Base as Curl;
use Eden\Facebook\Graph\Base as GraphBase;

/**
 * Facebook Graph API
 *
 * @vendor Eden
 * @package Facebook
 * @author Ian Mark Muninio <ianmuninio@openovate.com>
 */
class Graph extends Base
{
    const INSTANCE = 0;
    const GRAPH_URL = 'https://graph.facebook.com/';

    protected $token = null;

    /**
     * Preloads the token
     * 
     * @param string
     */
    public function __construct($token)
    {
        Argument::i()
                ->test(1, 'string'); // argument 1 must be a string

        $this->token = $token;
    }

    /**
     * Returns the facebook object.
     * 
     * @param type $name name of the facebook object
     * @param type $args the constructor args
     * @return FacebookObject
     */
    public function __call($name, $args)
    {
        return GraphBase::i($this->token)
                        ->__call($name, $args);
    }
    
    /**
     * Deletes an object based on id.
     * 
     * @param type $id id of the object
     * @param type $connection [optional] the connection
     * @return array|bool
     */
    public function delete($id, $connection = null)
    {
        Argument::i()
                ->test(1, 'string'); // argument 1 must be a string
        
        $url = self::GRAPH_URL . '/' . $id;

        if ($connection) {
            $url .= '/' . $connection;
        }
        
        $url .= '?access_token=' . $this->token;

        return $this->getResponse($url, array(), Curl::DELETE);
    }

    /**
     * Returns specific fields of an object
     *
     * @param string|int
     * @param string|array
     * @return array
     */
    public function getFields($id = 'me', $fields)
    {
        //Argument test
        Argument::i()
                ->test(1, 'string', 'int') // argument 1 must be a string or int
                ->test(2, 'string', 'array'); // argument 2 must be a string or array
        //if fields is an array	
        if (is_array($fields)) {
            //make it into a string
            $fields = implode(',', $fields);
        }

        //call it
        return $this->getObject($id, null, array('fields' => $fields));
    }

    /**
     * Returns the detail of any object.
     *
     * @param string|int [defaul: me] id of the object
     * @param string|null [optional] the page name
     * @param array [optional] the query
     * @param bool [default: true] required auth
     * 
     * @return array json object
     */
    public function getObject($id = 'me', $connection = null, array $query = array(), $auth = true)
    {
        Argument::i()
                ->test(1, 'string', 'int') // argument 1 must be a string or int
                ->test(2, 'string', 'null') // argument 2 must be a string or null
                ->test(3, 'array') // argument 3 must be an array
                ->test(4, 'bool'); // argument 4 must be a boolean
        // if we have a connection
        if ($connection) {
            //prepend a slash
            $connection = '/' . $connection;
        }

        // for the url
        $url = self::GRAPH_URL . $id . $connection;

        // if this requires authentication
        if ($auth) {
            // add the token
            $query['access_token'] = $this->token;
        }

        // if we have a query
        if (!empty($query)) {
            //append it to the url
            $url .= '?' . http_build_query($query);
        }

        // call it
        $object = $this->getResponse($url, array());

        return $object;
    }

    /**
     * Get response using curl.
     * 
     * @param type $url graph url
     * @param array $post post fields
     * @param type $request the request method
     * 
     * @return array jsonobject
     */
    protected function getResponse($url, array $post = array(), $request = Curl::GET)
    {
        //send it off
        $curl = Curl::i()
                ->setUrl($url)
                ->setConnectTimeout(10)
                ->setFollowLocation(true)
                ->setTimeout(60)
                ->verifyPeer(false)
                ->setUserAgent(Auth::USER_AGENT)
                ->setHeaders('Expect');

        switch ($request) {
            case Curl::PUT:
                $curl->setCustomPut();
                break;
            case Curl::GET:
                $curl->setCustomGet();
                break;
            case Curl::DELETE:
                $curl->setCustomDelete();
                break;
            case Curl::POST:
                $curl->setPost(true)
                        ->setPostFields(http_build_query($post));
                break;
            default:
        }

        $response = $curl->getJsonResponse();

        return $response;
    }

}