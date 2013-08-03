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
class PostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Post
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
     * @covers Eden\Facebook\Graph\Post::create
     * @todo   Implement testCreate().
     */
    public function testCreate()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setLink('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setIcon('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.14.173.173/s160x160/389598_337883976283546_406376820_n.jpg')
                ->setPicture('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $postId = $post->create();

        $this->assertTrue($graph->delete($postId));
    }

    /**
     * @covers Eden\Facebook\Graph\Post::setDescription
     * @todo   Implement testSetDescription().
     */
    public function testSetDescription()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setLink('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setIcon('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.14.173.173/s160x160/389598_337883976283546_406376820_n.jpg')
                ->setPicture('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $post);
    }

    /**
     * @covers Eden\Facebook\Graph\Post::setIcon
     * @todo   Implement testSetIcon().
     */
    public function testSetIcon()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setLink('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setIcon('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.14.173.173/s160x160/389598_337883976283546_406376820_n.jpg')
                ->setPicture('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $post);
    }

    /**
     * @covers Eden\Facebook\Graph\Post::setId
     * @todo   Implement testSetId().
     */
    public function testSetId()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setLink('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setIcon('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.14.173.173/s160x160/389598_337883976283546_406376820_n.jpg')
                ->setPicture('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $post);
    }

    /**
     * @covers Eden\Facebook\Graph\Post::setLink
     * @todo   Implement testSetLink().
     */
    public function testSetLink()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setLink('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setIcon('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.14.173.173/s160x160/389598_337883976283546_406376820_n.jpg')
                ->setPicture('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $post);
    }

    /**
     * @covers Eden\Facebook\Graph\Post::setPicture
     * @todo   Implement testSetPicture().
     */
    public function testSetPicture()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setLink('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setIcon('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.14.173.173/s160x160/389598_337883976283546_406376820_n.jpg')
                ->setPicture('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $post);
    }

    /**
     * @covers Eden\Facebook\Graph\Post::setTitle
     * @todo   Implement testSetTitle().
     */
    public function testSetTitle()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setLink('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setIcon('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.14.173.173/s160x160/389598_337883976283546_406376820_n.jpg')
                ->setPicture('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $post);
    }

    /**
     * @covers Eden\Facebook\Graph\Post::setVideo
     * @todo   Implement testSetVideo().
     */
    public function testSetVideo()
    {
        $graph = eden('facebook')->graph($this->token);
        $post = $graph->post('pogi si ian')
                ->setId($this->userId)
                ->setTitle('UnitTest')
                ->setDescription('UnitTest')
                ->setLink('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setIcon('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.14.173.173/s160x160/389598_337883976283546_406376820_n.jpg')
                ->setPicture('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc3/c24.15.188.188/s160x160/970818_613189255368639_2001237536_n.jpg')
                ->setVideo('http://www.youtube.com/watch?v=_Nfmjysyw7I');

        $this->assertInstanceOf('Eden\\Facebook\\Graph\\Post', $post);
    }
}
