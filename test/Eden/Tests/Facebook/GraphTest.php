<?php

namespace Eden\Facebook;

//-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class GraphTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Graph
     */
    protected $photoName = 'photos_24.png';
    protected $eventId = '';
    protected $pictureId = '';
    protected $userId = '';
    protected $token = '';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Eden\Facebook\Graph::addAlbum
     * @todo   Implement testAddAlbum().
     */
    public function testAddAlbum()
    {
//        $graph = eden('facebook')->graph($this->token);
//        $albumId = $graph->addAlbum($this->userId, '<name>', '<message>');
    }

    /**
     * @covers Eden\Facebook\Graph::addComment
     * @todo   Implement testAddComment().
     */
    public function testAddComment()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $postId = $post->create();

        $commentId = $graph->addComment($postId, 'test comment');

        $this->assertTrue($graph->delete($commentId));
        $this->assertTrue($graph->delete($postId));
    }

    /**
     * @covers Eden\Facebook\Graph::attendEvent
     * @todo   Implement testAttendEvent().
     */
    public function testAttendEvent()
    {
        $graph = eden('facebook')->graph($this->token);
        $response = $graph->attendEvent($this->eventId);

        $this->assertTrue($response);
    }

    /**
     * @covers Eden\Facebook\Graph::createNote
     * @todo   Implement testCreateNote().
     */
    public function testCreateNote()
    {
        $graph = eden('facebook')->graph($this->token);
        $noteId = $graph->createNote($this->userId, '<subject>', '<message>');

        $this->assertTrue($graph->delete($noteId));
    }

    /**
     * @covers Eden\Facebook\Graph::declineEvent
     * @todo   Implement testDeclineEvent().
     */
    public function testDeclineEvent()
    {
        $graph = eden('facebook')->graph($this->token);
        $response = $graph->declineEvent($this->eventId);

        $this->assertTrue($response);
    }

    /**
     * @covers Eden\Facebook\Graph::event
     * @todo   Implement testEvent().
     */
    public function testEvent()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2012-07-04');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph::getFields
     * @todo   Implement testGetFields().
     */
    public function testGetFields()
    {
        $graph = eden('facebook')->graph($this->token);
        $response = $graph->getFields($this->eventId, array());

        $this->assertEquals($this->eventId, $response['id']);
    }

    /**
     * @covers Eden\Facebook\Graph::getLogoutUrl
     * @todo   Implement testGetLogoutUrl().
     */
    public function testGetLogoutUrl()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Eden\Facebook\Graph::getObject
     * @todo   Implement testGetObject().
     */
    public function testGetObject()
    {
        $graph = eden('facebook')->graph($this->token);
        $response = $graph->getObject($this->eventId, 'attending');

        $this->assertArrayHasKey('data', $response);
    }

    /**
     * @covers Eden\Facebook\Graph::getPermissions
     * @todo   Implement testGetPermissions().
     */
    public function testGetPermissions()
    {
        $graph = eden('facebook')->graph($this->token);
        $response = $graph->getPermissions();

        $this->assertArrayHasKey(0, $response);
    }

    /**
     * @covers Eden\Facebook\Graph::getPictureUrl
     * @todo   Implement testGetPictureUrl().
     */
    public function testGetPictureUrl()
    {
        $graph = eden('facebook')->graph($this->token);
        $url = $graph->getPictureUrl($this->pictureId);
        $this->assertEquals('https://graph.facebook.com/' . $this->pictureId . '/picture?access_token=' . $this->token, $url);
    }

    /**
     * @covers Eden\Facebook\Graph::getUser
     * @todo   Implement testGetUser().
     */
    public function testGetUser()
    {
        $graph = eden('facebook')->graph($this->token);
        $response = $graph->getUser();

        $this->assertArrayHasKey('id', $response);
    }

    /**
     * @covers Eden\Facebook\Graph::like
     * @todo   Implement testLike().
     */
    public function testLike()
    {

        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $postId = $post->create();

        $this->assertTrue($graph->like($postId));
        $this->assertTrue($graph->unlike($postId));

        $this->assertTrue($graph->delete($postId));
    }

    /**
     * @covers Eden\Facebook\Graph::link
     * @todo   Implement testLink().
     */
    public function testLink()
    {
        $class = eden('facebook')->link('208640612627477', 'http://localhost:8080/');
        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Link', $class);
    }

    /**
     * @covers Eden\Facebook\Graph::maybeEvent
     * @todo   Implement testMaybeEvent().
     */
    public function testMaybeEvent()
    {
        $graph = eden('facebook')->graph($this->token);
        $response = $graph->maybeEvent($this->eventId);

        $this->assertTrue($response);
    }

    /**
     * @covers Eden\Facebook\Graph::post
     * @todo   Implement testPost().
     */
    public function testPost()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $post);
    }

    /**
     * @covers Eden\Facebook\Graph::uploadPhoto
     * @todo   Implement testUploadPhoto().
     */
    public function testUploadPhoto()
    {
//        $file = 'C:\\Users\\Openovate\\Documents\\NetBeansProjects\\Eden3\\Facebook\\test\Eden\\assets\\photos_24.png';
//        $graph = eden('facebook')->graph($this->token);
//        $albumId = $graph->addAlbum($this->userId, '<name>', '<message>');
//        $photoId = $graph->uploadPhoto($albumId, $file, '<message>');
    }
}
