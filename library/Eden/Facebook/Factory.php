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

use Eden\Facebook\Fql\Fql;
use Eden\Facebook\Graph\Event;
use Eden\Facebook\Graph\Link;
use Eden\Facebook\Graph\Post;
use Eden\Facebook\Graph\Subscribe;

/**
 * Facebook API factory. This is a factory class with 
 * methods that will load up different Facebook classes.
 * Facebook classes are organized as described on their 
 * developer site: auth, graph, FQL. We also added a post 
 * class for more advanced options when posting to Facebook.
 *
 * @vendor  Eden
 * @package Eden\Facebook
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Factory extends Base
{

    /**
     * Returns Facebook Auth
     *
     * @param string
     * @param string
     * @param string
     * @return Auth
     */
    public function auth($key, $secret, $redirect)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'string'); // argument 3 must be a string

        return Auth::i($key, $secret, $redirect);
    }

    /**
     * Returns the feed class
     * 
     * @param int $id
     * @return Feed
     * @author Ian Mark Muninio <ianmuninio@openovate.com>
     * @since 3.0.0
     */
    public function feed($id)
    {
        return Feed::i($id);
    }

    /**
     * Add an event
     *
     * @param string
     * @param string
     * @param string|int
     * @param string|int
     * @return Event
     */
    public function event($token, $name, $start, $end = null)
    {
        return Event::i($token, $name, $start, $end);
    }

    /**
     * Returns Facebook FQL
     *
     * @param string
     * @return Fql
     */
    public function fql($token)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string
        return Fql::i($token);
    }

    /**
     * Returns Facebook Graph
     *
     * @param string
     * @return Graph
     */
    public function graph($token)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string
        return Graph::i($token);
    }

    /**
     * Add a link
     *
     * @param string
     * @param string
     * @return Link
     */
    public function link($token, $url)
    {
        return Link::i($token, $url);
    }

    /**
     * Returns Facebook Post
     *
     * @param string
     * @param string
     * @return Post
     */
    public function post($token, $message)
    {
        return Post::i($token, $message);
    }

    /**
     * Returns Facebook subscribe
     *
     * @param string
     * @param string
     * @return Subscribe
     */
    public function subscribe($clientId, $secret)
    {
        return Subscribe::i($clientId, $secret);
    }
}