<?php

namespace Eden\Facebook;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-09-29 at 08:59:20.
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    protected $accessToken = '';
    protected $appToken = '';

    /**
     * @var Base
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
     * @covers Eden\Facebook\Base::debugToken
     */
    public function testDebugToken()
    {
        if (empty($this->accessToken) || empty($this->appToken)) {
            $this->assertTrue(false, 'Please set your application token and admin token.');
            return;
        }

        $base = Base::i();

        $response = $base->debugToken($this->accessToken, $this->appToken);

        $this->assertTrue(isset($response['data']),
                                isset($response['error']) ? $response['error']['message'] : '');
    }

}
