<?php

//-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Eden\Facebook\Fql\Search;

class SearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Search
     */
    protected $token;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $appId = '206915732766089';
        $secret = 'e1d0c383066dd0d3fab35ec9436130cb';
        $redirect = 'http://localhost:8080';
        $this->token = eden('facebook')->auth($appId, $secret, $redirect)
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
     * @covers Eden\Facebook\Fql\Search::addFilter
     * @todo   Implement testAddFilter().
     */
    public function testAddFilter()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->addSort('last_name')
                ->addSort('first_name', Search::DESC)
                ->addFilter('last_name', '');
        $this->assertAttributeEquals(array(array('last_name', '')), 'filter', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::addSort
     * @todo   Implement testAddSort().
     */
    public function testAddSort()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->addSort('last_name')
                ->addSort('first_name', Search::DESC);
        $this->assertAttributeEquals(array('last_name' => 'ASC', 'first_name' => 'DESC'), 'sort', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::getCollection
     * @todo   Implement testGetCollection().
     */
    public function testGetCollection()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->setTable('application')
                ->addFilter('app_id = 206915732766089')
                ->getCollection();

        $this->assertAttributeCount(1, 'list', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::getRows
     * @todo   Implement testGetRows().
     */
    public function testGetRows()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->setTable('application')
                ->addFilter('app_id = 206915732766089')
                ->getRows();

        $this->assertEquals('ianmuninio@aim.com', $object[0]['contact_email']);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::getTotal
     * @todo   Implement testGetTotal().
     */
    public function testGetTotal()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->setTable('application')
                ->addFilter('app_id = 206915732766089')
                ->getTotal();

        $this->assertEquals(1, $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::group
     * @todo   Implement testGroup().
     */
    public function testGroup()
    {
        $fql = eden('facebook')->fql($this->token);
        $object = $fql
                ->search()
                ->setTable('application')
                ->addFilter('app_id = 206915732766089')
                ->group('last');

        $this->assertAttributeSame($fql, 'database', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setColumns
     * @todo   Implement testSetColumns().
     */
    public function testSetColumns()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->setColumns('id', 'username')
                ->setTable('profile_pic')
                ->addFilter('id = me()');
        $this->assertAttributeEquals(array('id', 'username'), 'columns', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setPage
     * @todo   Implement testSetPage().
     */
    public function testSetPage()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->setColumns('id', 'username')
                ->setTable('profile_pic')
                ->setRange(4)
                ->setPage(5)
                ->addFilter('id = me()');
        $this->assertAttributeEquals(16, 'start', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setRange
     * @todo   Implement testSetRange().
     */
    public function testSetRange()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->setColumns('id', 'username')
                ->setTable('profile_pic')
                ->setRange(4)
                ->setPage(5)
                ->addFilter('id = me()');
        $this->assertAttributeEquals(4, 'range', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setStart
     * @todo   Implement testSetStart().
     */
    public function testSetStart()
    {
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->setColumns('id', 'username')
                ->setTable('profile_pic')
                ->setRange(4)
                ->setPage(5)
                ->setStart(6)
                ->addFilter('id = me()');
        $this->assertAttributeEquals(6, 'start', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setTable
     * @todo   Implement testSetTable().
     */
    public function testSetTable()
    {
        $table = 'openovate';
        $object = eden('facebook')->fql($this->token)
                ->search()
                ->setTable($table);
        $this->assertAttributeEquals($table, 'table', $object);
    }
}
