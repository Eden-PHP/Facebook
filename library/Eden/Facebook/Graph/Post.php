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
 * Create Facebook post
 *
 * @vendor  Eden
 * @package Eden\Facebook\Graph
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Post extends Base
{
    protected $token = null;
    protected $id = 'me';
    protected $post = array();

    /**
     * Preloads the id and post
     * 
     * @param string $token
     * @param string $message
     */
    public function __construct($token, $message)
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string');

        $this->token = $token;
        $this->post['message'] = $message;
    }

    /**
     * Sends the post to facebook
     *
     * @return this
     */
    public function create()
    {
        //get the facebook graph url
        $url = Graph::GRAPH_URL . $this->id . '/feed';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        //send it into curl
        $response = Curl::i()
                ->setUrl($url) //sets the url
                ->setConnectTimeout(10) //sets connection timeout to 10 sec.
                ->setFollowLocation(true) //sets the follow location to true 
                ->setTimeout(60) //set page timeout to 60 sec
                ->verifyPeer(false) //verifying Peer must be boolean
                ->setUserAgent(Auth::USER_AGENT) //set facebook USER_AGENT
                ->setHeaders('Expect') //set headers to EXPECT
                ->setPost(true) //set post to true
                ->setPostFields(http_build_query($this->post)) //set post fields
                ->getJsonResponse(); //get the json response

        return $response;         //return the id
    }

    /**
     * Sets media description
     *
     * @param string
     * @return this
     */
    public function setDescription($description)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->post['description'] = $description;
        return $this;
    }

    /**
     * Sets anicon for this post
     *
     * @param string
     * @return this
     */
    public function setIcon($url)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'url'); // argument 1 must be a url

        $this->post['icon'] = $url;
        return $this;
    }

    /**
     * Sets the id of the post
     * 
     * @param type $id
     * @return this
     */
    public function setId($id)
    {
        Argument::i()->test(1, 'numeric');

        $this->id = $id;
        return $this;
    }

    /**
     * Sets the link to your post
     *
     * @param string
     * @return this
     */
    public function setLink($url)
    {
        Argument::i()->test(1, 'url'); // argument 1 must be a url

        $this->post['link'] = $url;
        return $this;
    }

    /**
     * Sets the picture to your post
     *
     * @param string
     * @return this
     */
    public function setPicture($url)
    {
        Argument::i()->test(1, 'url'); // argument 1 must be a url

        $this->post['picture'] = $url;
        return $this;
    }

    /**
     * Sets the title of a post
     *
     * @param string
     * @return this
     */
    public function setTitle($title)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->post['title'] = $title;
        return $this;
    }

    /**
     * Sets the video to your post
     *
     * @param string
     * @return this
     */
    public function setVideo($url)
    {
        Argument::i()->test(1, 'url'); // argument 1 must be a url

        $this->post['video'] = $url;
        return $this;
    }
}