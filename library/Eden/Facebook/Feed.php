<?php

//-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Facebook;

use Eden\Utility\Curl;

/**
 * Facebook Feed
 *
 * @vendor  Eden
 * @package Eden\Facebook
 * @author  Ian Mark Muninio <ianmuninio@opengate.com>
 * @since   3.0.0
 */
class Feed extends Base
{
    const RSS_FORMAT = 'rss20';
    const JSON_FORMAT = 'json';
    const FEED_URL = 'https://www.facebook.com/feeds/page.php?id=%s&format=%s';
    const USER_AGENT = 'Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.10 (maverick) Firefox/3.6.13';
    protected $id = null;

    public function __construct($id)
    {
        Argument::i()->test(1, 'int');
        $this->id = $id;
    }

    /**
     * Returns an RSS feed to a public id
     *
     * @param int
     * @return SimpleXml
     */
    public function getRss()
    {
        return Curl::i()
                        ->setUrl(sprintf(self::FEED_URL, $this->id, self::RSS_FORMAT))
                        ->setUserAgent(self::USER_AGENT)
                        ->setConnectTimeout(10)
                        ->setFollowLocation(true)
                        ->setTimeout(60)
                        ->verifyPeer(false)
                        ->getSimpleXmlResponse();
    }

    /**
     * Returns an JSON feed to a public id
     *
     * @param int
     * @return mixed
     */
    public function getJson()
    {
        return Curl::i()
                        ->setUrl(sprintf(self::FEED_URL, $this->id, self::JSON_FORMAT))
                        ->setUserAgent(self::USER_AGENT)
                        ->setConnectTimeout(10)
                        ->setFollowLocation(true)
                        ->setTimeout(60)
                        ->verifyPeer(false)
                        ->getJsonResponse();
    }
}