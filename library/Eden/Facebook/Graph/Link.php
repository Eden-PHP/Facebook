<?php

//-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Facebook\Graph;

use Eden\Facebook\Auth;
use Eden\Facebook\Base;
use Eden\Facebook\Graph;
use Eden\Utility\Curl;

/**
 * Create Facebook link
 *
 * @vendor  Eden
 * @package Eden\Facebook\Graph
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Link extends Base
{
    protected $token;
    protected $id = 'me';
    protected $post = array();

    /**
     * Preloads the token and url
     * 
     * @param string $token
     * @param url $url
     */
    public function __construct($token, $url)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'url'); // argument 2 must be a url

        $this->token = $token;
        $this->post = array('link' => $url);
    }

    /**
     * Sends the post to facebook
     *
     * @return int
     */
    public function create()
    {
        //get the facebook graph url
        $url = Graph::GRAPH_URL . $this->id . '/links';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        //send it into curl
        $response = Curl::i()
                ->setUrl($url)          //sets the url
                ->setConnectTimeout(10)        //sets connection timeout to 10 sec.
                ->setFollowLocation(true)       //sets the follow location to true 
                ->setTimeout(60)         //set page timeout to 60 sec
                ->verifyPeer(false)         //verifying Peer must be boolean
                ->setUserAgent(Auth::USER_AGENT)  //set facebook USER_AGENT
                ->setHeaders('Expect')        //set headers to EXPECT
                ->setPost(true)          //set post to true
                ->setPostFields(http_build_query($this->post))  //set post fields
                ->getJsonResponse();        //get the json response
        if (array_key_exists('error', $response)) {
            throw Argument::i($response['error']['message']);
        }
        return $response['id'];         //return the id
    }

    /**
     * Sets a picture caption
     *
     * @param string
     * @return this
     */
    public function setCaption($caption)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->post['caption'] = $caption;
        return $this;
    }

    /**
     * Sets description
     *
     * @param string
     * @return this
     */
    public function setDescription($description)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->post['description'] = $description;
        return $this;
    }

    /**
     * Set the profile id
     *
     * @param int|string
     * @return this
     */
    public function setId($id)
    {
        Argument::i()->test(1, 'int', 'string'); // argument 1 must be a int or string
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the link title
     *
     * @param string
     * @return this
     */
    public function setName($name)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->post['name'] = $name;
        return $this;
    }

    /**
     * Sets a picture
     *
     * @param string
     * @return this
     */
    public function setPicture($picture)
    {
        Argument::i()->test(1, 'url'); // argument 1 must be a url

        $this->post['picture'] = $picture;
        return $this;
    }
}