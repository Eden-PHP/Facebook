<?php

//-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

use Eden\Facebook\Fql;

class FqlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Fql
     */
    protected $token;
    protected $appId;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->appId = '208640612627477';
        $secret = 'e5aeaa7cd6b2e40b88a24f202b3463c7';
        $redirect = 'http://localhost:8080/';
        $this->token = eden('facebook')->auth($this->appId, $secret, $redirect)
                ->getAppToken();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::getCollection
     * @todo   Implement testGetCollection().
     */
    public function testGetCollection()
    {
        $data = eden('facebook')
                ->fql($this->token)
                ->getCollection('application', array('app_id = ' . $this->appId));

        $this->assertAttributeCount(1, 'list', $data);
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::getModel
     * @todo   Implement testGetModel().
     */
    public function testGetModel()
    {
        $class = eden('facebook')
                ->fql($this->token);
        $model = $class->getModel('application', 'app_id', $this->appId);

        $this->assertAttributeContains('contact_email', 'data', $model);
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::getRow
     * @todo   Implement testGetRow().
     */
    public function testGetRow()
    {
        $data = eden('facebook')
                ->fql($this->token)
                ->getRow('application', 'app_id', $this->appId);

        $this->assertEquals('edenunittesting@gmail.com', $data['contact_email']);
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::getRows
     * @todo   Implement testGetRows().
     */
    public function testGetRows()
    {
        $data = eden('facebook')
                ->fql($this->token)
                ->getRows('application', array('app_id = ' . $this->appId));

        $this->assertEquals('edenunittesting@gmail.com', $data[0]['contact_email']);
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::getRowsCount
     * @todo   Implement testGetRowsCount().
     */
    public function testGetRowsCount()
    {
        $count = eden('facebook')
                ->fql($this->token)
                ->getRowsCount('application', array('app_id = ' . $this->appId));

        $this->assertEquals(1, $count);
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::getQueries
     * @todo   Implement testGetQueries().
     */
    public function testGetQueries()
    {
        $class = eden('facebook')
                ->fql($this->token);

        $query = $class->select('username, uid')
                ->from('user')
                ->where('uid = 100000327111827');
        $data = $class->query($query);

        $queries = $class->getQueries();

        $this->assertEquals($data, $queries[0]['results']['data']);
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::query
     * @todo   Implement testQuery().
     */
    public function testQuery()
    {
        $class = eden('facebook')
                ->fql($this->token);

        $query = $class->select('username, uid')
                ->from('user')
                ->where('uid = 100000327111827');
        $data = $class->query($query);
        $this->assertEquals('ianmuninio', $data[0]['username']);
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::search
     * @todo   Implement testSearch().
     */
    public function testSearch()
    {
        $class = eden('facebook')->fql($this->token)
                ->search();

        $this->assertInstanceOf('Eden\\Facebook\\Fql\\Search', $class);
    }

    /**
     * @covers Eden\Facebook\Fql\Fql::select
     * @todo   Implement testSelect().
     */
    public function testSelect()
    {
        $class = eden('facebook')->fql($this->token)->select();
        $this->assertInstanceOf('Eden\\Facebook\\Fql\\Select', $class);
    }
}
