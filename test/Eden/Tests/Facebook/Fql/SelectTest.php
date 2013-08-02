<?php

//-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class SelectTest extends \PHPUnit_Framework_TestCase
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
     * @covers Eden\Facebook\Fql\Select::from
     * @todo   Implement testFrom().
     */
    public function testFrom()
    {
        $object = eden('facebook')->fql('token')
                ->select('username')
                ->from('pogi')
                ->where('password = \'mali\'');
        $this->assertAttributeSame('pogi', 'from', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Select::limit
     * @todo   Implement testLimit().
     */
    public function testLimit()
    {
        $object = eden('facebook')->fql('token')
                ->select()
                ->limit(2, 123);
        $this->assertAttributeSame(2, 'page', $object);
        $this->assertAttributeSame(123, 'length', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Select::getQuery
     * @todo   Implement testGetQuery().
     */
    public function testGetQuery()
    {
        $object = eden('facebook')->fql('token')
                ->select('*')
                ->from('pogi')
                ->where('password = \'mali\'');

        $object2 = eden('facebook')->fql('token')
                ->select('*')
                ->from('pogi')
                ->where('password = \'mali\'');
        $this->assertEquals($object, $object2);
    }

    /**
     * @covers Eden\Facebook\Fql\Select::select
     * @todo   Implement testSelect().
     */
    public function testSelect()
    {
        $object = eden('facebook')->fql('token')
                ->select('username');
        $this->assertAttributeSame('username', 'select', $object);

        $object = $object
                ->select(array('ianmuninio', 'pogi'));
        $this->assertAttributeSame('ianmuninio, pogi', 'select', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Select::sortBy
     * @todo   Implement testSortBy().
     */
    public function testSortBy()
    {
        $object = eden('facebook')->fql('token')
                ->select('username')
                ->from('pogi')
                ->where('password = \'mali\'')
                ->sortBy('what_the', 'DESC')
                ->sortBy('the_what');

        $this->assertAttributeEquals(array('what_the DESC', 'the_what ASC'), 'sortBy', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Select::where
     * @todo   Implement testWhere().
     */
    public function testWhere()
    {
        $object = eden('facebook')->fql('token')
                ->select('username')
                ->from('pogi')
                ->where('password = \'mali\'');
        $this->assertAttributeSame(array('password = \'mali\''), 'where', $object);
    }
}
