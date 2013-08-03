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
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Event
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
     * @covers Eden\Facebook\Graph\Event::create
     * @todo   Implement testCreate().
     */
    public function testCreate()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setId($this->userId);

        $eventId = $event->create();

        $this->assertTrue($graph->delete($eventId));
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setCity
     * @todo   Implement testSetCity().
     */
    public function testSetCity()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCity('city')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setCoordinates
     * @todo   Implement testSetCoordinates().
     */
    public function testSetCoordinates()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->setCity('city')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setCountry
     * @todo   Implement testSetCountry().
     */
    public function testSetCountry()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->setCity('city')
                ->setCountry('philippines')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setDescription
     * @todo   Implement testSetDescription().
     */
    public function testSetDescription()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->setCity('city')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setId
     * @todo   Implement testSetId().
     */
    public function testSetId()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->setCity('city')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setLocation
     * @todo   Implement testSetLocation().
     */
    public function testSetLocation()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setLocation('Openovate')
                ->setCoordinates(1.2, 2.3)
                ->setCity('city')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setPrivacyClosed
     * @todo   Implement testSetPrivacyClosed().
     */
    public function testSetPrivacyClosed()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->SetPrivacyClosed()
                ->setCity('city')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setPrivacyOpen
     * @todo   Implement testSetPrivacyOpen().
     */
    public function testSetPrivacyOpen()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->SetPrivacyOpen()
                ->setCity('city')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setPrivacySecret
     * @todo   Implement testSetPrivacySecret().
     */
    public function testSetPrivacySecret()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->SetPrivacySecret()
                ->setCity('city')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setState
     * @todo   Implement testSetState().
     */
    public function testSetState()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->SetPrivacyClosed()
                ->setCity('city')
                ->setState('STRESSED')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }

    /**
     * @covers Eden\Facebook\Graph\Event::setStreet
     * @todo   Implement testSetStreet().
     */
    public function testSetStreet()
    {
        $graph = eden('facebook')->graph($this->token);
        $event = $graph->event('unit test', '2013-08-10')
                ->setDescription('UnitTest')
                ->setCoordinates(1.2, 2.3)
                ->SetPrivacyClosed()
                ->setCity('city')
                ->setState('STRESSED')
                ->setStreet('Mandaluyong')
                ->setId($this->userId);

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Event', $event);
    }
}
