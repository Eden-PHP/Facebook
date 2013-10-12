<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Facebook;

/**
 * Facebook API factory. This is a factory class with
 * methods that will load up different Facebook classes.
 * Facebook classes are organized as described on their
 * developer site: auth, graph, FQL. We also added a post
 * class for more advanced options when posting to Facebook.
 *
 * @vendor Eden
 * @package Facebook
 * @author Ian Mark Muninio <ianmuninio@openovate.com>
 */
class Factory extends Base
{
    /**
     * Returns the instance of Auth
     *
     * @param string $key      the key of the application
     * @param string $secret   the secret of the application
     * @param string $redirect the redirect url of the page
     * @return \Eden\Facebook\Auth
     */
    public function auth($key, $secret, $redirect)
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string')
                ->test(3, 'url');

        return Auth::i($key, $secret, $redirect);
    }

    /**
     * Returns the instance of feed
     *
     * @param string $id the id of the page
     * @return \Eden\Facebook\Feed
     */
    public function feed($id)
    {
        Argument::i()->test(1, 'string');

        return Feed::i($id);
    }

    /**
     * Returns the instance of graph.
     *
     * @param string $token access token
     * @return \Eden\Facebook\Graph
     */
    public function graph($token)
    {
        Argument::i()->test(1, 'string');

        return Graph::i($token);
    }

    /**
     * Returns the instance of fql.
     *
     * @param string $token access token
     * @return \Eden\Facebook\Fql
     */
    public function fql($token)
    {
        Argument::i()->test(1, 'string');

        return Fql::i($token);
    }
}
