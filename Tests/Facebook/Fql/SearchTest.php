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
    protected $key = '';
    protected $secret = '';
    protected $redirect = '';
    protected $code = '';
    protected $token = '';
    protected $search;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        if (!empty($this->token)) {
            $this->search = eden('facebook')
                    ->fql($this->token)
                    ->search();
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
        if ((empty($this->code) || empty($this->token))) {
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
     * @covers Eden\Facebook\Fql\Search::addFilter
     */
    public function testAddFilter()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
                ->addSort('last_name')
                ->addSort('first_name', Search::DESC)
                ->addFilter('last_name', '');

        $this->assertAttributeEquals(array(array('last_name', '')), 'filter', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::addSort
     */
    public function testAddSort()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
                ->addSort('last_name')
                ->addSort('first_name', Search::DESC);

        $this->assertAttributeEquals(array('last_name' => 'ASC', 'first_name' => 'DESC'), 'sort',
                                     $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::getCollection
     */
    public function testGetCollection()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
                ->setTable('application')
                ->addFilter('app_id = ' . $this->key)
                ->getCollection();

        $this->assertAttributeCount(1, 'list', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::getRows
     */
    public function testGetRows()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
                ->setTable('status')
                ->addFilter('uid = me()')
                ->getRows();

        $this->assertTrue(is_array($object));
    }

    /**
     * @covers Eden\Facebook\Fql\Search::getTotal
     */
    public function testGetTotal()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
                ->setTable('status')
                ->addFilter('uid = me()')
                ->getTotal();

        $this->assertEquals(0, $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::group
     */
    public function testGroup()
    {
        if (empty($this->token)) {
            return;
        }

        $object = $this->search
                ->setTable('application')
                ->addFilter('app_id = ' . $this->key)
                ->group('last');

        $this->assertAttributeEquals(array(), 'columns', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setColumns
     */
    public function testSetColumns()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
                ->setColumns('id', 'username')
                ->setTable('profile_pic')
                ->addFilter('id = me()');

        $this->assertAttributeEquals(array('id', 'username'), 'columns', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setPage
     */
    public function testSetPage()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
                ->setColumns('id', 'username')
                ->setTable('profile_pic')
                ->setRange(4)
                ->setPage(5)
                ->addFilter('id = me()');

        $this->assertAttributeEquals(16, 'start', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setRange
     */
    public function testSetRange()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
                ->setColumns('id', 'username')
                ->setTable('profile_pic')
                ->setRange(4)
                ->setPage(5)
                ->addFilter('id = me()');

        $this->assertAttributeEquals(4, 'range', $object);
    }

    /**
     * @covers Eden\Facebook\Fql\Search::setStart
     */
    public function testSetStart()
    {
        if (empty($this->token)) {
            return;
        }
        $object = $this->search
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
     */
    public function testSetTable()
    {
        if (empty($this->token)) {
            return;
        }
        $table = 'openovate';
        $object = $this->search
                ->setTable($table);

        $this->assertAttributeEquals($table, 'table', $object);
    }

}
