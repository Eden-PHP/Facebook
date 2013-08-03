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
    const FEED = 'feed';
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

        parent::__construct($token, self::FEED);
    }

    /**
     * Sends the post to facebook
     *
     * @return this
     */
    public function create()
    {
        return parent::getResponse($this->id, $this->post);          //return the id
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
        Argument::i()->test(1, 'string');

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