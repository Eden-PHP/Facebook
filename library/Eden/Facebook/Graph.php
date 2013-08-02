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

use Eden\Facebook\Base;
use Eden\Facebook\Graph\Argument as GraphArgument;
use Eden\Facebook\Graph\Event;
use Eden\Facebook\Graph\Link;
use Eden\Facebook\Graph\Post;
use Eden\Utility\Curl;

/**
 * Facebook Graph API
 *
 * @vendor  Eden
 * @package Eden\Facebook
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Graph extends Base
{
    const GRAPH_URL = 'https://graph.facebook.com/';
    const LOGOUT_URL = 'https://www.facebook.com/logout.php?next=%s&access_token=%s';
    protected $token = null;

    /**
     * Preloads the token
     * 
     * @param type $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Magic metthod of __call
     * 
     * @param type $name
     * @param type $args
     * @return type
     */
    public function __call($name, $args)
    {
        //if the method starts with get
        if (strpos($name, 'get') === 0 && in_array(substr($name, 3), $this->_list)) {
            $key = preg_replace("/([A-Z])/", "/$1", $name);
            //get rid of get
            $key = strtolower(substr($key, 4));

            $id = 'me';
            if (!empty($args)) {
                $id = array_shift($args);
            }

            array_unshift($args, $id, $key);

            return call_user_func_array(array($this, 'getDataList'), $args);
        } else if (strpos($name, 'search') === 0 && in_array(substr($name, 6), $this->_search)) {

            //get rid of get
            $key = strtolower(substr($name, 6));

            array_unshift($args, $key);

            return call_user_func_array(array($this, 'searchData'), $args);
        }
    }

    /**
     * Add an album
     *
     * @param string|int the object ID to place the album
     * @param string
     * @param string the album description
     * @return int the album ID
     */
    public function addAlbum($id, $name, $message)
    {
        //GraphArgument test
        GraphArgument::i()
                ->test(1, 'string', 'int') // argument 1 must be a string or integer
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'string'); // argument 3 must be a string
        //form the URL
        $url = self::GRAPH_URL . $id . '/albums';
        $post = array('name' => $name, 'message' => $message);
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);
        $results = json_decode($this->getCurlResponse($url, $post), true);

        return $results['id'];
    }

    /**
     * Adds a comment to a post
     *
     * @param int the post ID commenting on
     * @param string
     * @return int the comment ID
     */
    public function addComment($id, $message)
    {
        //GraphArgument test
        GraphArgument::i()
                ->test(1, 'int') // argument 1 must be an integer
                ->test(2, 'string'); // argument 2 must be a string
        //form the URL	
        $url = self::GRAPH_URL . $id . '/comments';
        $post = array('message' => $message);
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);
        $results = json_decode($this->getCurlResponse($url, $post), true);

        return $results['id'];
    }

    /**
     * Attend an event
     *
     * @param int the event ID
     * @return this
     */
    public function attendEvent($id)
    {
        GraphArgument::i()->test(1, 'int');

        $url = self::GRAPH_URL . $id . '/attending';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        json_decode($this->getCurlResponse($url), true);

        return $this;
    }

    /**
     * Check into a place
     *
     * @param string|int the checkin ID
     * @param string 
     * @param float
     * @param float
     * @param int the place ID
     * @param string|array
     * @return int
     */
    public function checkin($id, $message, $latitude, $longitude, $place, $tags)
    {
        //GraphArgument test
        GraphArgument::i()
                ->test(1, 'string', 'int') // argument 1 must be a string or integer
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'float') // argument 3 must be a float
                ->test(4, 'float') //GraphArgument 4 must be a float
                ->test(5, 'int') //GraphArgument 5 must be a integer
                ->test(6, 'string', 'array'); // argument 6 must be a string or an array

        $url = self::GRAPH_URL . $id . '/checkins';
        $post = array('message' => $message);
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        //if message
        if ($message) {
            //add it
            $post['message'] = $message;
        }

        //if coords
        if ($latitude && $longitute) {
            //add it
            $post['coordinates'] = json_encode(array(
                'latitude' => $latitude,
                'longitude' => $longitude));
        }

        //if place
        if ($place) {
            //add it
            $post['place'] = $place;
        }

        //if tags
        if ($tags) {
            //add it
            $post['tags'] = $tags;
        }

        $results = json_decode($this->getCurlResponse($url, $post), true);
        return $results['id'];
    }

    /**
     * Add a note
     *
     * @param int|string object ID where to put the note
     * @param string
     * @param string
     * @return int
     */
    public function createNote($id = 'me', $subject, $message)
    {
        GraphArgument::i()
                ->test(1, 'string', 'int') // argument 1 must be a string or integer
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'string'); // argument 3 must be a string
        //form the URL	
        $url = self::GRAPH_URL . $id . '/notes';
        $post = array('subject' => $subject, 'message' => $message);
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);
        $results = json_decode($this->getCurlResponse($url, $post), true);

        return $results['id'];
    }

    /**
     * Decline an event
     *
     * @param int event ID
     * @return this
     */
    public function declineEvent($id)
    {
        GraphArgument::i()->test(1, 'int'); // argument 1 must be a inteeger
        $url = self::GRAPH_URL . $id . '/declined';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        json_decode($this->getCurlResponse($url), true);

        return $this;
    }

    /**
     * Add an event
     *
     * @param string name of event
     * @param string|int string date or time format
     * @param string|int string date or time format
     * @return Event
     */
    public function event($name, $start, $end)
    {
        return Event::i($this->token, $name, $start, $end);
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
        //GraphArgument test
        GraphArgument::i()
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
     * Returns the logout URL
     *
     * @param string
     * @return string
     */
    public function getLogoutUrl($redirect)
    {
        GraphArgument::i()->test(1, 'url');
        return sprintf(self::LOGOUT_URL, urlencode($redirect), $this->token);
    }

    /**
     * Returns the detail of any object
     *
     * @param string|int
     * @param string|null
     * @param array
     * @param bool
     * @return array
     */
    public function getObject($id = 'me', $connection = null, array $query = array(), $auth = true)
    {
        GraphArgument::i()
                ->test(1, 'string', 'int') // argument 1 must be a string or int
                ->test(2, 'string', 'null') // argument 2 must be a string or null
                ->test(3, 'array') // argument 3 must be an array
                ->test(4, 'bool'); // argument 4 must be a boolean
        //if we have a connection	
        if ($connection) {
            //prepend a slash
            $connection = '/' . $connection;
        }

        //for the url
        $url = self::GRAPH_URL . $id . $connection;

        //if this requires authentication
        if ($auth) {
            //add the token
            $query['access_token'] = $this->token;
        }

        //if we have a query
        if (!empty($query)) {
            //append it to the url
            $url .= '?' . http_build_query($query);
        }

        //call it
        $object = $this->getCurlResponse($url);
        $object = json_decode($object, true);

        //if there is an error
        if (isset($object['error'])) {
            //throw it
            GraphArgument::i()
                    ->setMessage(GraphArgument::GRAPH_FAILED)
                    ->addVariable($url)
                    ->addVariable($object['error']['type'])
                    ->addVariable($object['error']['message'])
                    ->trigger();
        }

        return $object;
    }

    /**
     * Returns user permissions
     *
     * @param string|int
     * @return array
     */
    public function getPermissions($id = 'me')
    {
        GraphArgument::i()->test(1, 'string', 'int'); // argument 1 must be a string or an integer
        $permissions = $this->getObject($id, 'permissions');
        return $permissions['data'];
    }

    /**
     * Returns the user's image
     *
     * @param string|int
     * @param bool
     * @return string
     */
    public function getPictureUrl($id = 'me', $token = true)
    {
        //GraphArgument test
        GraphArgument::i()
                ->test(1, 'string', 'int') // argument 1 must be a string or an integer
                ->test(2, 'bool'); // argument 2 must be a boolean
        //for the URL	
        $url = self::GRAPH_URL . $id . '/picture';

        //if this needs a token
        if ($token) {
            //add it
            $url .= '?access_token=' . $this->token;
        }

        return $url;
    }

    /**
     * Returns the user info
     *
     * @return array
     */
    public function getUser()
    {
        return $this->getObject('me');
    }

    /**
     * Like an object
     *
     * @param int|string object ID
     * @return array
     */
    public function like($id)
    {
        GraphArgument::i()->test(1, 'string', 'int');
        $url = self::GRAPH_URL . $id . '/likes';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        $this->getCurlResponse($url);
        return $this;
    }

    /**
     * Add a link
     *
     * @param string
     * @return Link
     */
    public function link($url)
    {
        return Link::i($this->token, $url);
    }

    /**
     * Maybe an event
     *
     * @param int event ID
     * @return this
     */
    public function maybeEvent($id)
    {
        GraphArgument::i()->test(1, 'int'); // argument 1 must be an integer

        $url = self::GRAPH_URL . $id . '/maybe';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        json_decode($this->getCurlResponse($url), true);

        return $this;
    }

    /**
     * Returns Facebook Post
     *
     * @param string
     * @return Post
     */
    public function post($message)
    {
        return Post::i($this->token, $message);
    }

    /**
     * Uploads a file of your album
     *
     * @param int|string
     * @param string
     * @param string|null
     * @return int photo ID
     */
    public function uploadPhoto($albumId, $file, $message = null)
    {
        GraphArgument::i()
                ->test(1, 'string', 'int') // argument 1 must be a string or integer
                ->test(2, 'file') // argument 2 must be a file
                ->test(3, 'string', 'null'); // argument 3 must be a string or null
        //form the URL
        $url = self::GRAPH_URL . $albumId . '/photos';
        $post = array('source' => '@' . $file);
        $query = array('access_token' => $this->token);

        //if there is a message
        if ($message) {
            $post['message'] = $message;
        }

        $url .= '?' . http_build_query($query);

        //send it off
        $results = Curl::i()
                ->setUrl($url)
                ->setConnectTimeout(10)
                ->setFollowLocation(true)
                ->setTimeout(60)
                ->verifyPeer(false)
                ->setUserAgent(Auth::USER_AGENT)
                ->setHeaders('Expect')
                ->when(!empty($post), 2)
                ->setPost(true)
                ->setPostFields($post)
                ->getJsonResponse();

        return $results['id'];
    }

    /**
     * Gets the response of the curl
     * 
     * @param type $url
     * @param array $post
     * @return type
     */
    protected function getCurlResponse($url, array $post = array())
    {
        return Curl::i()
                        ->setUrl($url)
                        ->setConnectTimeout(10)
                        ->setFollowLocation(true)
                        ->setTimeout(60)
                        ->verifyPeer(false)
                        ->setUserAgent(Auth::USER_AGENT)
                        ->setHeaders('Expect')
                        ->when(!empty($post), 2)
                        ->setPost(true)
                        ->setPostFields(http_build_query($post))
                        ->getResponse();
    }

    /**
     * Gets the list of data
     * 
     * @param type $id
     * @param type $connection
     * @param type $start
     * @param type $range
     * @param type $since
     * @param type $until
     * @param type $dateFormat
     * @return type
     */
    protected function getDataList($id, $connection, $start = 0, $range = 0, $since = 0, $until = 0, $dateFormat = null)
    {
        $query = array();
        if ($start > 0) {
            $query['offset'] = $start;
        }

        if ($range > 0) {
            $query['limit'] = $range;
        }

        if (is_string($since)) {
            $since = strtotime($since);
        }

        if (is_string($until)) {
            $until = strtotime($until);
        }

        if ($since !== 0) {
            $query['since'] = $since;
        }

        if ($until !== 0) {
            $query['until'] = $until;
        }

        $list = $this->getObject($id, $connection, $query);

        return $list['data'];
    }

    protected function searchData($connection, $query, $fields = null)
    {
        $query = array('type' => $connection, 'q' => $query);

        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }

        if ($fields) {
            $query['fields'] = $fields;
        }

        $results = $this->getObject('search', null, $query);

        return $results['data'];
    }
    protected $_list = array(
        'Friends', 'Home',
        'Feed', 'Likes',
        'Movies', 'Music',
        'Books', 'Photos',
        'Albums', 'Videos',
        'VideoUploads', 'Events',
        'Groups', 'Checkins');
    protected $_search = array(
        'Posts', 'Users',
        'Pages', 'Likes',
        'Places', 'Events',
        'Groups', 'Checkins');
}