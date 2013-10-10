<?php //-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Facebook;

use Eden\Curl\Base as Curl;

/**
 * Facebook Page Feed
 *
 * @vendor Eden
 * @package Facebook
 * @author Ian Mark Muninio <ianmuninio@opengate.com>
 */
class Feed extends Base
{
    const RSS_FORMAT = 'rss20';
    const JSON_FORMAT = 'json';
    const FEED_URL = 'https://www.facebook.com/feeds/page.php?id=%s&format=%s';
    const USER_AGENT = 'facebook-php-eden';

    protected $id = null;

    /**
     * Sets the id of the page.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        Argument::i()
                ->test(1, 'number'); // argument 1 must be a number

        $this->id = $id;
    }

    /**
     * Returns the SimpleXML Format feed of the page.
     *
     * @return \SimpleXMLElement the feed of the page on xml format
     */
    public function getRss()
    {
        $results = Curl::i()
                ->setUrl(sprintf(self::FEED_URL, $this->id, self::RSS_FORMAT))
                ->setUserAgent(self::USER_AGENT)
                ->setConnectTimeout(10)
                ->setFollowLocation(true)
                ->setTimeout(60)
                ->verifyPeer(false)
                ->getSimpleXmlResponse();

        return $results;
    }

    /**
     * Returns the JSON format feed of the page.
     *
     * @return array the feed of the page on json format
     */
    public function getJson()
    {
        $results = Curl::i()
                ->setUrl(sprintf(self::FEED_URL, $this->id, self::JSON_FORMAT))
                ->setUserAgent(self::USER_AGENT)
                ->setConnectTimeout(10)
                ->setFollowLocation(true)
                ->setTimeout(60)
                ->verifyPeer(false)
                ->getJsonResponse();

        return $results;
    }
}
