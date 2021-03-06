<?php

namespace Eden\Facebook;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-09-29 at 06:50:16.
 */
class GraphTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Graph
     */
    protected $key = '';
    protected $secret = '';
    protected $redirect = '';
    protected $code = '';
    protected $token = '';
    protected $graph;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        if (!empty($this->token)) {
            $this->graph = eden('facebook')
                    ->graph($this->token);
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    public function testCode()
    {
        if (empty($this->code) && empty($this->token)) {
            $scope = array(
                'create_event',
                'publish_stream',
                'can_upload'
            );

            $url = eden('facebook')
                    ->auth($this->key, $this->secret, $this->redirect)
                    ->getLoginUrl($scope);

            $this->assertTrue(false, 'Please login to this url: ' . $url);
        }
    }

    public function testToken()
    {
        if (empty($this->code) || empty($this->token)) {
            $auth = eden('facebook')
                    ->auth($this->key, $this->secret, $this->redirect);

            $accessToken = $auth->getAccess($this->code);

            if (isset($accessToken['access_token'])) {
                $this->assertTrue(false, 'Your access token: ' . $accessToken['access_token']);
            } else {
                $meta = $auth->getMeta();

                $url = $meta['url'];
                $url .= '?' . http_build_query($meta['query']);

                $this->assertTrue(false,
                                  'Put the access code and get the access token from this: ' . $url);
            }
        }
    }

    /**
     * @covers Eden\Facebook\Graph::album
     */
    public function testAlbum()
    {
        if (empty($this->token)) {
            return;
        }

        $album = $this->graph
                ->albums('Hello');

        $response = $album->setMessage('World')
                ->setPrivacy('SELF')
                ->create();

        $this->assertTrue(isset($response['id']), isset($response['error']) ? $response['error']['message'] : '');
        echo 'Skipping deleting the album. API doesn\'t have capabilities to delete albums.';
    }

    /**
     * @covers Eden\Facebook\Graph::event
     */
    public function testEvent()
    {
        if (empty($this->token)) {
            return;
        }

        $response = $this->graph
                ->events('Hello', '2013-12-10T14:00:02+0000')
                ->setEndTime('2013-12-15T20:00:02+0000')
                ->setDescription('World')
                ->create();

        $this->assertTrue(isset($response['id']),
                                isset($response['error']) ? $response['error']['message'] : '');

        $id = $response['id'];

        $this->assertTrue(!isset($response['error']) || $response === true,
                                 isset($response['error']) ? $response['error']['message'] : '');
    }

    /**
     * @covers Eden\Facebook\Graph::notes
     */
    public function testNote()
    {
        if (empty($this->token)) {
            return;
        }

        $response = $this->graph
                ->notes('Hello', 'World')
                ->create();

        $this->assertTrue(isset($response['id']),
                                isset($response['error']) ? $response['error']['message'] : '');

        $id = $response['id'];

        $response = $this->graph->delete($id);
        $this->assertTrue(!isset($response['error']) || $response === true,
                                 isset($response['error']) ? $response['error']['message'] : '');
    }

    /**
     * @covers Eden\Facebook\Graph::photos
     */
    public function testPhoto()
    {
//        if (empty($this->token)) {
//            return;
//        }
//
//        $response = $this->graph
//                ->photos(__DIR__ . '/../assets/photos_24.png', 'Hello World', 'BSA Twin Tower',
//                         false)
//                ->create();
//
//        $this->assertTrue(isset($response['id']),
//                                isset($response['error']) ? $response['error']['message'] : '');
//
//        $id = $response['id'];
//
//        $response = $this->graph->delete($id);
//        $this->assertTrue(!isset($response['error']) || $response === true,
//                                 isset($response['error']) ? $response['error']['message'] : '');
    }

    /**
     * @covers Eden\Facebook\Graph::feed|comment|like
     */
    public function testFeedAndCommentAndLike()
    {
        if (empty($this->token)) {
            return;
        }

        $response = $this->graph
                ->feed()
                ->setLink('http://eden.openovate.com/')
                ->setPicture('http://eden.openovate.com/')
                ->setName('Eden Name')
                ->setCaption('Eden Caption')
                ->setDescription('Eden Description')
                ->create();

        $this->assertTrue(isset($response['id']),
                                isset($response['error']) ? $response['error']['message'] : '');

        $id = $response['id'];

        $response = $this->graph
                ->comments('Hello World Comment')
                ->setId($id)
                ->create();
        $this->assertTrue(!isset($response['error']) || $response === true,
                                 isset($response['error']) ? $response['error']['message'] : '');

        $response = $this->graph
                ->delete($response['id']);
        $this->assertTrue(!isset($response['error']) || $response === true,
                                 isset($response['error']) ? $response['error']['message'] : '');

        $response = $this->graph
                ->likes()
                ->setId($id)
                ->create();
        $this->assertTrue(!isset($response['error']) || $response === true,
                                 isset($response['error']) ? $response['error']['message'] : '');

        $response = $this->graph
                ->delete($id, 'likes');
        $this->assertTrue(!isset($response['error']) || $response === true,
                                 isset($response['error']) ? $response['error']['message'] : '');

        $response = $this->graph
                ->delete($id);
        $this->assertTrue(!isset($response['error']) || $response === true,
                                 isset($response['error']) ? $response['error']['message'] : '');
    }

    /**
     * @covers Eden\Facebook\Graph::scores
     */
    public function testScore()
    {
        // Uncomment this line for game test
//        if (empty($this->token)) {
//            return;
//        }
//
//        $response = $this->graph
//                ->scores(99, 'http://eden.openovate.com/')
//                ->create();
//
//        $this->assertTrue(isset($response['id']),
//                                isset($response['error']) ? $response['error']['message'] : '');
//
//        $id = $response['id'];
//
//        $response = $this->graph->delete($id);
//        $this->assertTrue(!isset($response['error']) || $response === true,
//                                 isset($response['error']) ? $response['error']['message'] : '');
    }

    /**
     * @covers Eden\Facebook\Graph::achievement
     */
    public function testAchievement()
    {
        // Uncomment this line for game test
//        if (empty($this->token)) {
//            return;
//        }
//
//        $response = $this->graph
//                ->achievements('http://eden.openovate.com/')
//                ->create();
//
//        $this->assertTrue(isset($response['id']),
//                                isset($response['error']) ? $response['error']['message'] : '');
//
//        $id = $response['id'];
//
//        $response = $this->graph->delete($id);
//        $this->assertTrue(!isset($response['error']) || $response === true,
//                                 isset($response['error']) ? $response['error']['message'] : '');
    }

    /**
     * @covers Eden\Facebook\Graph::getFields
     */
    public function testGetFields()
    {
        if (empty($this->token)) {
            return;
        }

        $response = $this->graph
                ->getFields('me', array('id', 'name'));

        $this->assertTrue(isset($response['id']),
                                isset($response['error']) ? $response['error']['message'] : '');
    }

    /**
     * @covers Eden\Facebook\Graph::getObject
     */
    public function testGetObject()
    {
        if (empty($this->token)) {
            return;
        }

        $response = $this->graph
                ->getObject('me', null, array('id', 'name'));

        $this->assertTrue(isset($response['id']),
                                isset($response['error']) ? $response['error']['message'] : '');
    }

}
