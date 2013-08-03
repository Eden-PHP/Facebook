<?php

//-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Facebook\Graph;

use Eden\Facebook\Auth;
use Eden\Facebook\Base as FacebookBase;
use Eden\Facebook\Exception;
use Eden\Facebook\Graph;
use Eden\Utility\Curl;

/**
 * The base class for all classes wishing to integrate with Eden.
 * Extending this class will allow your methods to seemlessly be
 * overloaded and overrided as well as provide some basic class
 * loading patterns.
 *
 * @vendor  Eden
 * @package Eden\Facebook\Graph
 * @author  Ian Mark Muninio <ianmuninio@openovate.com>
 * @since   3.0.0
 */
class Base extends FacebookBase
{
    protected $token;
    protected $type;

    public function __construct($token, $type)
    {
        $this->token = $token;
        $this->type = $type;
    }

    protected function getResponse($id, $post)
    {
        //get the facebook graph url
        $url = Graph::GRAPH_URL . $id . '/' . $this->type;
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        //send it into curl
        $response = Curl::i()
                ->setUrl($url) // sets the url
                ->setConnectTimeout(10) // sets connection timeout to 10 sec.
                ->setFollowLocation(true) // sets the follow location to true 
                ->setTimeout(60) // set page timeout to 60 sec
                ->verifyPeer(false) // verifying Peer must be boolean
                ->setUserAgent(Auth::USER_AGENT) // set facebook USER_AGENT
                ->setHeaders('Expect') // set headers to EXPECT
                ->setPost(true) // set post to true
                ->setPostFields(http_build_query($post)) // set post fields
                ->getJsonResponse(); // get the json response

        if (isset($response['error']['message'])) {
            Exception::i()
                    ->setMessage($response['error']['message'])
                    ->trigger();
        }

        return $response['id'];
    }
}