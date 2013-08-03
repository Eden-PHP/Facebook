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
 * Create Facebook Event
 *
 * @vendor  Eden
 * @package Eden
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Event extends Base
{
    const EVENTS = 'events';
    const OPEN = 'OPEN';
    const CLOSED = 'CLOSED';
    const SECRET = 'SECRET';
    protected $token = null;
    protected $id = 'me';
    protected $post = array();
    protected $venue = array();

    /**
     * Preloads the token and post
     * 
     * @param type $token
     * @param type $name
     * @param type $start
     * @param type $end
     */
    public function __construct($token, $name, $start, $end = null)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'string') // argument 3 must be a string or an integer
                ->test(4, 'string', 'null'); // argument 4 must be a string, an integer or a null

        $this->token = $token;
        $this->post = array(
            'name' => $name,
            'start_time' => $start,
            'end_time' => $end);

        parent::__construct($token, self::EVENTS);
    }

    /**
     * Sends the post to facebook
     *
     * @return int
     */
    public function create()
    {
        //post variable must be array
        $post = $this->post;

        if (!empty($this->venue)) {
            $post['venue'] = json_encode($this->venue);
        }

        return $this->getResponse($this->id, $post);
    }

    /**
     * Sets the venue city
     *
     * @param string
     * @return this
     */
    public function setCity($city)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->venue['city'] = $city;
        return $this;
    }

    /**
     * Sets the venue coordinates
     *
     * @param float
     * @param float
     * @return this
     */
    public function setCoordinates($latitude, $longitude)
    {
        Argument::i()
                ->test(1, 'float') // argument 1 must be a float
                ->test(2, 'float'); // argument 2 must be a float

        $this->venue['latitude'] = $latitude;
        $this->venue['longitude'] = $longitude;
        return $this;
    }

    /**
     * Sets the venue country
     *
     * @param string
     * @return this
     */
    public function setCountry($country)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->venue['country'] = $country;
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
     * Profile Id
     * 
     * @param type $id
     * @return \Eden\Facebook\Graph\Event
     */
    public function setId($id)
    {
        Argument::i()->test(1, 'string', 'numeric'); // argument 1 must be a string or a numeric

        $this->id = $id;
        return $this;
    }

    /**
     * Sets the title of a post
     *
     * @param string
     * @return this
     */
    public function setLocation($location)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->post['location'] = $location;
        return $this;
    }

    /**
     * Sets privacy to closed
     *
     * @return this
     */
    public function setPrivacyClosed()
    {
        $this->post['privacy'] = self::CLOSED;
        return $this;
    }

    /**
     * Sets privacy to open
     *
     * @return this
     */
    public function setPrivacyOpen()
    {
        $this->post['privacy'] = self::OPEN;
        return $this;
    }

    /**
     * Sets privacy to secret
     *
     * @return this
     */
    public function setPrivacySecret()
    {
        $this->post['privacy'] = self::SECRET;
        return $this;
    }

    /**
     * Sets the venue state
     *
     * @param string
     * @return this
     */
    public function setState($state)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');

        $this->venue['state'] = $state;
        return $this;
    }

    /**
     * Sets the venue street
     *
     * @param string
     * @return this
     */
    public function setStreet($street)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');

        $this->venue['street'] = $street;
        return $this;
    }
}