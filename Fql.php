<?php // -->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Facebook;

use Eden\Facebook\Argument;
use Eden\Facebook\Auth;
use Eden\Facebook\Base;
use Eden\Facebook\Fql\Search;
use Eden\Facebook\Fql\Select;
use Eden\Collection\Base as Collection;
use Eden\Curl\Base as Curl;
use Eden\Model\Base as Model;

/**
 * Abstractly defines a layout of available methods to
 * connect to and query a database. This class also lays out
 * query building methods that auto renders a valid query
 * the specific database will understand without actually
 * needing to know the query language.
 *
 * @vendor Eden
 * @package Facebook
 * @author Ian Mark Muninio <ianmuninio@openovate.com>
 */
class Fql extends Base
{
    const INSTANCE = 0; // set to multiton
    const SELECT = 'Select';
    const FQL_URL = 'https://graph.facebook.com/fql';
    const FIRST = 'first';
    const LAST = 'last';

    protected $queries = array();
    protected $token = null;

    /**
     * Preloads the access token.
     * 
     * @param string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Gets the result of the query and parse it to collection/model.
     * 
     * @param string       $table
     * @param string|array $filters [optional]
     * @param array        $sort
     * @param int          $start
     * @param int          $range
     * @param int          $index [optional]
     * @return \Eden\Model\Base|\Eden\Collection\Base
     */
    public function getCollection(
            $table, 
            $filters = null, 
            array $sort = array(), 
            $start = 0,
            $range = 0, 
            $index = null
    ) {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string', 'array', 'null')
                ->test(4, 'numeric')
                ->test(5, 'numeric')
                ->test(6, 'numeric', 'null');

        $results = $this->getRows($table, $filters, $sort, $start, $range, $index);

        $collection = Collection::i();

        // checks if results is null
        if (is_null($results)) {
            return $collection; // return empty collection
        }

        // checks if index is not null
        if (!is_null($index)) {
            return $this->model($results); // return model with results
        }

        return $collection->set($results); // return collection with results
    }

    /**
     * Gets a model from the result.
     * 
     * @param string        $table
     * @param string        $name
     * @param string|number $value
     * @return \Eden\Model\Base
     */
    public function getModel($table, $name, $value)
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string')
                ->test(3, 'string', 'numeric');

        $result = $this->getRow($table, $name, $value);

        $model = Model::i();

        if (is_null($result)) {
            return $model;
        }

        return $model->set($result);
    }

    /**
     * Gets a single result of the query.
     * 
     * @param string        $table
     * @param string        $name
     * @param string|number $value
     * @return array
     */
    public function getRow($table, $name, $value)
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string')
                ->test(3, 'string', 'numeric');

        // select query
        $query = $this->select()
                ->from($table)
                ->where($name . ' = ' . $value)
                ->limit(0, 1);

        $results = $this->query($query);

        return isset($results[0]) ? $results[0] : null;
    }

    /**
     * Gets the results of the query.
     * 
     * @param string       $table
     * @param string|array $filters [optional]
     * @param int|float    $sort
     * @param int|float    $start
     * @param int|float    $range
     * @param int|float    $index   [optional]
     * @return array returns null if the results is empty
     */
    public function getRows(
            $table,
            $filters = null, 
            array $sort = array(), 
            $start = 0, 
            $range = 0, 
            $index = null
    ) {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string', 'array', 'null')
                ->test(4, 'numeric')
                ->test(5, 'numeric')
                ->test(6, 'numeric', 'null');

        $query = $this->select()->from($table);

        // if filters is an array
        if (is_array($filters)) {
            // for each filter
            foreach ($filters as $i => $filter) {
                // skip if filter is not an array
                if (!is_array($filter)) {
                    continue;
                }

                // we want to transform it into a string
                // array('post_id=%s AND post_title IN %s', 123, array('asd'));
                $format = array_shift($filter);
                $filters[$i] = vsprintf($format, $filter);
            }
        }

        // at this point filters is either a string or null
        // if it's a string
        if (!is_null($filters)) {
            // add it to the where
            $query->where($filters);
        }

        // if we have sorting
        if (!empty($sort)) {
            // for each sort
            foreach ($sort as $key => $value) {
                // if it's a valid string
                if (is_string($key) && trim($key)) {
                    // add sort
                    $query->sortBy($key, $value);
                }
            }
        }

        // if there is a range
        if ($range) {
            // add the pagination
            $query->limit($start, $range);
        }

        // run the query
        $results = $this->query($query);

        // if index is set
        if (!is_null($index)) {
            // if there are no results anyways
            if (empty($results)) {
                // return null
                return null;
            }

            // if index equals string first
            if ($index == self::FIRST) {
                $index = 0;
                // else if index equals string last
            } elseif ($index == self::LAST) {
                $index = count($results) - 1;
            }

            // if the index is not set in results
            if (!isset($results[$index])) {
                return null;
            }

            $results = $results[$index];
        }

        return $results;
    }

    /**
     * Gets the total count of the query.
     * 
     * @param string       $table
     * @param string|array $filters [optional]
     * @return int
     */
    public function getRowsCount($table, $filters = null)
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string', 'array', 'null');

        $query = $this->select('COUNT(*)')->from($table);

        // if filters is an array
        if (is_array($filters)) {
            // for each filter
            foreach ($filters as $i => $filter) {
                // skip if filter is not an array
                if (!is_array($filter)) {
                    continue;
                }

                // we want to transform it into a string
                // array('post_id=%s AND post_title IN %s', 123, array('asd'));
                $format = array_shift($filter);
                $filters[$i] = vsprintf($format, $filter);
            }
        }

        // at this point filters is either a string or null
        // if it's a string
        if (!is_null($filters)) {
            // add it to the where
            $query->where($filters);
        }

        // run the query
        $results = $this->query($query);

        if (isset($results)) {
            return sizeOf($results);
        }

        return 0;
    }

    /**
     * Gets the fql queries.
     * 
     * @param string|int $index [optional]
     * @return array
     */
    public function getQueries($index = null)
    {
        if (is_null($index)) {
            return $this->queries;
        }

        if ($index == self::FIRST) {
            $index = 0; // gets the first query
        }

        if ($index == self::LAST) {
            $index = count($this->queries) - 1; // gets the last query
        }

        // if the query index exists
        if (isset($this->queries[$index])) {
            return $this->queries[$index];
        }

        return null;
    }

    /**
     * Gets the response with specified query.
     * 
     * @param string|array $query
     * @return array
     * @throws \Eden\Facebook\Exception if response message is error
     */
    public function query($query)
    {
        Argument::i()->test(1, 'string', 'array', self::SELECT);
        
        // if query is a string
        if (!is_array($query)) {
            $query = array('q' => (string) $query);
            // query is an array
        } else {
            // for the q
            foreach ($query as $key => $select) {
                $query[$key] = (string) $select;
            }

            $query = array('q' => json_encode($query));
        }

        // add the access token
        $query['access_token'] = $this->token;
        $url = self::FQL_URL . '?' . http_build_query($query);

        // get the results
        $results = Curl::i()
                ->setUrl($url)
                ->setConnectTimeout(10)
                ->setFollowLocation(true)
                ->setTimeout(60)
                ->verifyPeer(false)
                ->setUserAgent(Auth::USER_AGENT)
                ->setHeaders('Expect')
                ->getJsonResponse();

        // store a historical data on it
        $this->queries[] = array(
            'query' => $query['q'],
            'results' => $results);

        // if there is an error
        if (isset($results['error']['message'])) {
            // throw an exception
            Exception::i()
                    ->setMessage($results['error']['message'])
                    ->trigger();
        }

        return $results['data'];
    }

    /**
     * Returns a new instance of Search class.
     * 
     * @return \Eden\Facebook\Fql\Search
     */
    public function search()
    {
        return Search::i($this);
    }

    /**
     * Returns a new instance of Select class.
     *
     * @param string|array $select columns to be selected (Default: *)
     * @return \Eden\Facebook\Fql\Select
     */
    public function select($select = '*')
    {
        Argument::i()->test(1, 'string', 'array');

        return Select::i($select);
    }
}
