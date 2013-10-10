<?php //-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Facebook\Graph;

use Eden\Curl\Base as Curl;
use Eden\Facebook\Auth;
use Eden\Facebook\Base as FacebookBase;
use Eden\Facebook\Graph;

/**
 * The base class for all classes wishing to integrate with Eden.
 * Extending this class will allow your methods to seemlessly be
 * overloaded and overrided as well as provide some basic class
 * loading patterns.
 *
 * @vendor Eden
 * @package Facebook\Graph
 * @author Ian Mark Muninio <ianmuninio@openovate.com>
 */
class Base extends FacebookBase
{
    protected $id = 'me';
    protected $post = array();
    protected $token;
    protected $type;

    /**
     * Preloads the token of the graph.
     *
     * @param string $token
     * @return void
     */
    public function __construct($token)
    {
        Argument::i()->test(1, 'string');
        
        $this->token = $token;
    }

    /**
     * Sets the id of the object.
     *
     * @param string $id id of the facebook object
     * @return \Eden\Facebook\Graph\Base
     */
    public function setId($id)
    {
        Argument::i()->test(1, 'string');
        
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the json response of the request.
     *
     * @return array
     */
    protected function getResponse()
    {
        // get the facebook graph url
        $url = Graph::GRAPH_URL . $this->id . '/' . $this->type;
        $query = array('access_token' => $this->token);
        $url .= '?' . http_build_query($query);

        // send it into curl
        $response = Curl::i()
                ->setUrl($url) // sets the url
                ->setConnectTimeout(10) // sets connection timeout to 10 sec.
                ->setFollowLocation(true) // sets the follow location to true
                ->setTimeout(60) // set page timeout to 60 sec
                ->verifyPeer(false) // verifying Peer must be boolean
                ->setUserAgent(Auth::USER_AGENT) // set facebook USER_AGENT
                ->setHeaders('Expect') // set headers to EXPECT
                ->setPost(true) // set method to post
                ->setPostFields(http_build_query($this->post)) // set post fields
                ->getJsonResponse(); // get the json response

        return $response;
    }

    /**
     * Calls the facebook object if the name exists.
     *
     * @param string $name name of the facebook object
     * @param array $args the contructor arguments
     * @return \Eden\Facebook\Graph\FacebookObject|null
     */
    public function __call($name, array $args = array())
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'array');
        
        if (isset($this->_objects[$name])) {
            return FacebookObject::i($this->token, $name, $this->_objects[$name], $args);
        }
        
        return null;
    }

    /**
     * The facebook objects and its fields and properties.
     */
    protected $_objects = array(
        'likes' => array(),
        'comments' => array(
            'message' => array(
                'type' => 'string',
                'required' => true
            )
        ),
        'albums' => array(
            'name' => array(
                'type' => 'string',
                'required' => true
            ),
            'message' => array(
                'type' => 'string',
                'required' => false
            ),
            'privacy' => array(
                'type' => 'privacy',
                'required' => false
            )
        ),
        'events' => array(
            'name' => array(
                'type' => 'string',
                'required' => true
            ),
            'start_time' => array(
                'type' => 'string',
                'required' => true
            ),
            'end_time' => array(
                'type' => 'string',
                'required' => false
            ),
            'description' => array(
                'type' => 'string',
                'required' => false
            ),
            'location' => array(
                'type' => 'string',
                'required' => false
            ),
            'location_id' => array(
                'type' => 'string',
                'required' => false
            ),
            'privacy_type' => array(
                'type' => 'string',
                'required' => false
            ),
            // PAGE
            'ticket_uri' => array(
                'type' => 'url',
                'required' => false
            )
        ),
        'notes' => array(
            'subject' => array(
                'type' => 'string',
                'required' => true
            ),
            'message' => array(
                'type' => 'string',
                'required' => true
            )
        ),
        'notifications' => array(
            'template' => array(
                'type' => 'string',
                'required' => true
            ),
            'href' => array(
                'type' => 'string',
                'required' => true
            )
        ),
        'photos' => array(
            'source' => array(
                'type' => 'file',
                'required' => true
            ),
            'message' => array(
                'type' => 'string',
                'required' => false
            ),
            'place' => array(
                'type' => 'string',
                'required' => false
            ),
            'no_story' => array(
                'type' => 'bool',
                'required' => false
            ),
            // PAGE
            'published' => array(
                'type' => 'bool',
                'required' => false
            ),
            'scheduled_publish_time' => array(
                'type' => 'int',
                'required' => false
            )
        ),
        'feed' => array(
            'message' => array(
                'type' => 'string',
                'required' => false
            ),
            'link' => array(
                'type' => 'url',
                'required' => false
            ),
            'picture' => array(
                'type' => 'url',
                'required' => false
            ),
            'name' => array(
                'type' => 'string',
                'required' => false
            ),
            'caption' => array(
                'type' => 'string',
                'required' => false
            ),
            'description' => array(
                'type' => 'string',
                'required' => false
            ),
            'place' => array(
                'type' => 'string',
                'required' => false
            ),
            'tags' => array(
                'type' => 'array',
                'required' => false
            ),
            'privacy' => array(
                'type' => 'privacy',
                'required' => false
            ),
            'object_attachment' => array(
                'type' => 'string',
                'required' => false
            ),
            // PAGE
            'published' => array(
                'type' => 'bool',
                'required' => false
            ),
            'scheduled_publish_time' => array(
                'type' => 'int',
                'required' => false
            )
        ),
        'videos' => array(
            'source' => array(
                'type' => 'file',
                'required' => true
            ),
            'title' => array(
                'type' => 'string',
                'required' => false
            ),
            'description' => array(
                'type' => 'string',
                'required' => false
            ),
            // PAGE
            'published' => array(
                'type' => 'bool',
                'required' => false
            ),
            'scheduled_publish_time' => array(
                'type' => 'int',
                'required' => false
            )
        ),
        'scores' => array(
            'score' => array(
                'type' => 'int',
                'required' => true
            )
        ),
        'achievements' => array(
            'achievement' => array(
                'type' => 'url',
                'required' => true
            )
        ),
        // <---------------- PAGE
        'links' => array(
            'link' => array(
                'type' => 'url',
                'required' => true
            ),
            'message' => array(
                'type' => 'string',
                'required' => false
            ),
            'picture' => array(
                'type' => 'url',
                'required' => false
            ),
            'published' => array(
                'type' => 'bool',
                'required' => false
            ),
            'scheduled_publish_time' => array(
                'type' => 'int',
                'required' => false
            )
        ),
        'milestones' => array(
            'title' => array(
                'type' => 'string',
                'required' => true
            ),
            'description' => array(
                'type' => 'string',
                'required' => true
            ),
            'start_time' => array(
                'type' => 'string',
                'required' => true
            )
        ),
        'offers' => array(
            'title' => array(
                'type' => 'string',
                'required' => true
            ),
            'expiration_time' => array(
                'type' => 'string',
                'required' => true
            ),
            'terms' => array(
                'type' => 'string',
                'required' => false
            ),
            'image_url' => array(
                'type' => 'url',
                'required' => false
            ),
            'image' => array(
                'type' => 'file',
                'required' => false
            ),
            'claim_limit' => array(
                'type' => 'int',
                'required' => false
            ),
            'coupon_type' => array(
                'type' => 'string',
                'required' => false
            ),
            'qrcode' => array(
                'type' => 'alphnum',
                'required' => false
            ),
            'barcode' => array(
                'type' => 'string',
                'required' => false
            ),
            'redemption_link' => array(
                'type' => 'url',
                'required' => false
            ),
            'redemption_code' => array(
                'type' => 'string',
                'required' => false
            ),
            'published' => array(
                'type' => 'bool',
                'required' => false
            ),
            'scheduled_publish_time' => array(
                'type' => 'int',
                'required' => false
            ),
            'reminder_time' => array(
                'type' => 'string',
                'required' => false
            )
        ),
        'messages' => array(
            'message' => array(
                'type' => 'string',
                'required' => true
            )
        ),
        'tabs' => array(
            'app_id' => array(
                'type' => 'string',
                'required' => true
            ),
            // for updates
            'position' => array(
                'type' => 'iint',
                'required' => false
            ),
            'custom_name' => array(
                'type' => 'string',
                'required' => false
            ),
            'is_non_connection_landing_tab' => array(
                'type' => 'bool',
                'required' => false
            ),
            'custom_image_url' => array(
                'type' => 'url',
                'required' => false
            ),
            'custom_image' => array(
                'type' => 'file',
                'required' => false
            )
        )
    );
}
