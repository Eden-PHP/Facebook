<?php

namespace Eden\Facebook\Graph;

//-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class LinkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Link
     */
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
     * @covers Eden\Facebook\Graph\Link::create
     * @todo   Implement testCreate().
     */
    public function testCreate()
    {
        $graph = eden('facebook')->graph($this->token);
        $link = $graph->link('http://www.youtube.com/')
                ->setDescription('UnitTest')
                ->setId($this->userId);

        $linkId = $link->create();

        $this->assertTrue($graph->delete($linkId));
    }

    /**
     * @covers Eden\Facebook\Graph\Link::setCaption
     * @todo   Implement testSetCaption().
     */
    public function testSetCaption()
    {
        $graph = eden('facebook')->graph($this->token);
        $link = $graph->link('http://www.youtube.com/')
                ->setDescription('UnitTest')
                ->setId($this->userId)
                ->setCaption('caption');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Link', $link);
    }

    /**
     * @covers Eden\Facebook\Graph\Link::setDescription
     * @todo   Implement testSetDescription().
     */
    public function testSetDescription()
    {
        $graph = eden('facebook')->graph($this->token);
        $link = $graph->link('http://www.youtube.com/')
                ->setDescription('UnitTest')
                ->setId($this->userId)
                ->setCaption('caption')
                ->setDescription('desc');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Link', $link);
    }

    /**
     * @covers Eden\Facebook\Graph\Link::setId
     * @todo   Implement testSetId().
     */
    public function testSetId()
    {
        $graph = eden('facebook')->graph($this->token);
        $link = $graph->link('http://www.youtube.com/')
                ->setDescription('UnitTest')
                ->setId($this->userId)
                ->setCaption('caption')
                ->setDescription('desc');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Link', $link);
    }

    /**
     * @covers Eden\Facebook\Graph\Link::setName
     * @todo   Implement testSetName().
     */
    public function testSetName()
    {
        $graph = eden('facebook')->graph($this->token);
        $link = $graph->link('http://www.youtube.com/')
                ->setDescription('UnitTest')
                ->setId($this->userId)
                ->setCaption('caption')
                ->setDescription('desc')
                ->setDescription('name');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Link', $link);
    }

    /**
     * @covers Eden\Facebook\Graph\Link::setPicture
     * @todo   Implement testSetPicture().
     */
    public function testSetPicture()
    {
        $graph = eden('facebook')->graph($this->token);
        $link = $graph->link('http://www.youtube.com/')
                ->setDescription('UnitTest')
                ->setId($this->userId)
                ->setCaption('caption')
                ->setDescription('desc')
                ->setDescription('name')
                ->setDescription('http://www.youtube.com/image.jpg');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Link', $link);
    }
}
