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
 * Create Facebook link
 *
 * @vendor  Eden
 * @package Eden\Facebook\Graph
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Link extends Base
{
    const LINK = 'links';
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

        parent::__construct($token, self::LINK);
    }

    /**
     * Sends the post to facebook
     *
     * @return int
     */
    public function create()
    {
        return parent::getResponse($this->id, $this->post);         //return the id
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