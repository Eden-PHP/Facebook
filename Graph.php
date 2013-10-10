<?php //-->
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
    const INSTANCE = 0; // sets to multiton
    const GRAPH_URL = 'https://graph.facebook.com/';

    protected $token = null;

    /**
     * Preloads the token.
     *
     * @param string $token
     * @return void
     */
    public function __construct($token)
    {
        Argument::i()->test(1, 'string');

        $this->token = $token;
    }

    /**
     * Returns the facebook object.
     *
     * @param string $name name of the facebook object
     * @param scalar $args the constructor arguments
     * @return \Eden\Facebook\Graph\Base
     */
    public function __call($name, $args)
    {
        Argument::i()->test(1, 'string');
        
        return GraphBase::i($this->token)
                        ->__call($name, $args);
    }

    /**
     * Deletes an object based on id.
     *
     * @param string      $id         id of the object
     * @param string|null $connection [optional] the connection
     * @return array
     */
    public function delete($id, $connection = null)
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string', 'null');

        $url = self::GRAPH_URL . '/' . $id;

        if ($connection) {
            $url .= '/' . $connection;
        }

        $url .= '?access_token=' . $this->token;

        return $this->getResponse($url, array(), Curl::DELETE);
    }

    /**
     * Returns specific fields of an object.
     *
     * @param string|int   $id     [optional]
     * @param string|array $fields
     * @return array
     */
    public function getFields($id = 'me', $fields = array())
    {
        Argument::i()
                ->test(1, 'string', 'int')
                ->test(2, 'string', 'array');
        
        // if fields is an array
        if (is_array($fields)) {
            //make it into a string
            $fields = implode(',', $fields);
        }

        // call it
        return $this->getObject($id, null, array('fields' => $fields));
    }

    /**
     * Returns the detail of any object.
     *
     * @param string|int  $id         [optional] (defaul: me) id of the object
     * @param string|null $connection [optional] the page name
     * @param array       $query      [optional] the query
     * @param bool        $auth       [optional] (default: true) required auth
     * @return array json object
     */
    public function getObject($id = 'me', $connection = null, array $query = array(), $auth = true)
    {
        Argument::i()
                ->test(1, 'string', 'int')
                ->test(2, 'string', 'null')
                ->test(3, 'array')
                ->test(4, 'bool');
        
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
     * @param string $url     graph url
     * @param array  $post    post fields
     * @param string $request the request method
     * @return array
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
