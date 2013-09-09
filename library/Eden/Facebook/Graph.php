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
use Eden\Facebook\Graph\Event;
use Eden\Facebook\Graph\Link;
use Eden\Facebook\Graph\Post;
use Eden\Utility\Curl;

/**
 * Facebook Graph API
 *
 * @vendor  Eden
 * @package Eden\Facebook
 * @author  Ian Mark Muninio <ianmuninio@openovate.com>
 * @since   3.0.0
 */
class Graph extends Base
{
    const GRAPH_URL = 'https://graph.facebook.com/';
    const LOGOUT_URL = 'https://www.facebook.com/logout.php?next=%s&access_token=%s';
    protected $token = null;

    /**
     * Preloads the token
     * 
     * @param string
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Magic method of __call
     * 
     * @param string
     * @param array
     * @return mixed
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
     * Delete a facebook object
     * 
     * @param string $id object id
     * @return mixed reponse of the server
     */
    public function delete($id)
    {
        Argument::i()
                ->test(1, 'string'); // argument 1 must be a string
        //
        //get the facebook graph url
        $url = Graph::GRAPH_URL . $id;
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        return $this->getResponse($url, array(), Curl::DELETE);        
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
        //Argument test
        Argument::i()
			->test(1, 'string', 'int') // argument 1 must be a string or integer
			->test(2, 'string') // argument 2 must be a string
			->test(3, 'string'); // argument 3 must be a string
				
        //form the URL
        $url = self::GRAPH_URL . $id . '/albums';
        $post = array('name' => $name, 'message' => $message);
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        $response = $this->getResponse($url, $post);
		
		return $response['id'];
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
        //Argument test
        Argument::i()
			->test(1, 'string') // argument 1 must be an string
			->test(2, 'string'); // argument 2 must be a string
		
        //form the URL	
        $url = self::GRAPH_URL . $id . '/comments';
        $post = array('message' => $message);
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);
        $results = $this->getResponse($url, $post);

        if (isset($results['error']['message'])) {
            Exception::i()
				->setMessage($results['error']['message'])
				->trigger();
        }

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
        Argument::i()
			->test(1, 'string', 'int') // argument 1 must be a string or integer
			->test(2, 'string') // argument 2 must be a string
			->test(3, 'string'); // argument 3 must be a string
		
        //form the URL	
        $url = self::GRAPH_URL . $id . '/notes';
        $post = array('subject' => $subject, 'message' => $message);
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);
        $results = $this->getResponse($url, $post);

        return $results['id'];
    }

    /**
     * Add an event
     *
     * @param string name of event
     * @param string|int string date or time format
     * @param string|int string date or time format
     * @return Eden\Facebook\Graph\Event
     */
    public function event($name, $start, $end = null)
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
        //Argument test
        Argument::i()
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
        Argument::i()->test(1, 'url');
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
        Argument::i()
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
        $object = $this->getResponse($url, array(), Curl::GET);

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
		// argument 1 must be a string or an integer
        Argument::i()->test(1, 'string', 'int'); 
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
        //Argument test
        Argument::i()
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
        Argument::i()->test(1, 'string', 'int');
        $url = self::GRAPH_URL . $id . '/likes';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        return $this->getResponse($url);
    }

    /**
     * Unlike an object
     * 
     * @param int|string $id
     * @return array
     */
    public function unlike($id)
    {
        Argument::i()->test(1, 'string', 'int');
        $url = self::GRAPH_URL . $id . '/likes';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        return $this->getResponse($url, array(), Curl::DELETE);
    }

    /**
     * Add a link
     *
     * @param string
     * @return Eden\Facebook\Graph\Link
     */
    public function link($url)
    {
        return Link::i($this->token, $url);
    }

    /**
     * Attend an event
     *
     * @param int the event ID
     * @return array
     */
    public function attendEvent($id)
    {
        Argument::i()->test(1, 'int');

        $url = self::GRAPH_URL . $id . '/attending';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        return $this->getResponse($url);
    }

    /**
     * Decline an event
     *
     * @param int event ID
     * @return array
     */
    public function declineEvent($id)
    {
		// argument 1 must be a inteeger
        Argument::i()->test(1, 'int'); 
        $url = self::GRAPH_URL . $id . '/declined';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        return $this->getResponse($url);
    }

    /**
     * Maybe an event
     *
     * @param int event ID
     * @return array
     */
    public function maybeEvent($id)
    {
        Argument::i()->test(1, 'int'); // argument 1 must be an integer

        $url = self::GRAPH_URL . $id . '/maybe';
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        return $this->getResponse($url);
    }

    /**
     * Returns Facebook Post
     *
     * @param string
     * @return Eden\Facebook\Graph\Post
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
     * @return array
     */
    public function uploadPhoto($albumId, $file, $message = null)
    {
        Argument::i()
			->test(1, 'string', 'int') // argument 1 must be a string or integer
			->test(2, 'file') // argument 2 must be a file
			->test(3, 'string', 'null'); // argument 3 must be a string or null
		
        //form the URL
        $url = self::GRAPH_URL . $albumId . '/photos';
        $post = array('source' => $file);
        $query = array('access_token' => $this->token);
        $post['scope'] = 'publish_stream';

        var_dump(http_build_query($post));
        
        //if there is a message
        if ($message) {
            $post['message'] = $message;
        }

        $url .= '?' . http_build_query($query);

        //send it off
        return $this->getResponse($url, $post);
    }

    /**
     * Get response using curl
     * 
     * @param type $url
     * @param array $post
     * @param type $request
     * @return type
     */
    protected function getResponse($url, array $post = array(), $request = 'POST')
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
            case 'PUT':
                $curl->setCustomPut();
                break;
            case 'GET':
                $curl->setCustomGet();
                break;
            case 'DELETE':
                $curl->setCustomDelete();
                break;
            default:
                $curl->setPost(true)
                    ->setPostFields(http_build_query($post));
        }

        $response = $curl->getJsonResponse();

        if (isset($response['error']['message'])) {
            Exception::i()
				->setMessage($response['error']['message'])
				->trigger();
        }

        return $response;
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