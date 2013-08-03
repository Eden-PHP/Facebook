<?php

//-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{

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
     * @covers Eden\Facebook\Graph::auth
     * @todo   Implement testAuth().
     */
    public function testAuth()
    {
        $class = eden('facebook')->auth('key', 'secret', 'http://localhost:8080/');
        $this->assertInstanceOf('Eden\\Facebook\\Auth', $class);
    }

    /**
     * @covers Eden\Facebook\Graph::event
     * @todo   Implement testEvent().
     */
    public function testEvent()
    {
        $class = eden('facebook')->event('token', 'name', '2014-10-12');
        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $class);
    }

    /**
     * @covers Eden\Facebook\Fql::fql
     * @todo   Implement testFql().
     */
    public function testFql()
    {
        $class = eden('facebook')->fql('token');
        $this->assertInstanceOf('Eden\\Facebook\\Fql\\Fql', $class);
    }

    /**
     * @covers Eden\Facebook::feed
     * @todo   Implement testFeed().
     */
    public function testFeed()
    {
        $id = '100000327111827';
        $class = eden('facebook')->feed($id);
        $this->assertInstanceOf('Eden\\Facebook\\Feed', $class);
    }

    /**
     * @covers Eden\Facebook\Graph::graph
     * @todo   Implement testGraph().
     */
    public function testGraph()
    {
        $class = eden('facebook')->graph('token');
        $this->assertInstanceOf('Eden\\Facebook\\Graph', $class);
    }

    /**
     * @covers Eden\Facebook\Graph::link
     * @todo   Implement testLink().
     */
    public function testLink()
    {
        $class = eden('facebook')->link('token', 'http://localhost/');
        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Link', $class);
    }

    /**
     * @covers Eden\Facebook\Graph::post
     * @todo   Implement testPost().
     */
    public function testPost()
    {
        $class = eden('facebook')->post('token', 'message');
        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $class);
    }

    /**
     * @covers Eden\Facebook\Graph::subscribe
     * @todo   Implement testSubscribe().
     */
    public function testSubscribe()
    {
        $class = eden('facebook')->subscribe('208640612627477', 'e5aeaa7cd6b2e40b88a24f202b3463c7');
        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Subscribe', $class);
    }
}
