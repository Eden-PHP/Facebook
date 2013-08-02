<?php

//-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

use Eden\Facebook\Feed;

class FeedTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Feed
     */

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
     * @covers Eden\Facebook\Feed::getRss
     * @todo   Implement testGetRss().
     */
    public function testGetRss()
    {
        $feed = eden('facebook')
                ->feed('233662293387528')
                ->getRss();
        $this->assertTrue(isset($feed));
    }

    /**
     * @covers Eden\Facebook\Feed::getJson
     * @todo   Implement testGetJson().
     */
    public function testGetJson()
    {
        $feed = eden('facebook')
                ->feed('233662293387528')
                ->getJson();
        $this->assertTrue(isset($feed));
    }
}
