<?php

//-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Facebook\Fql;

use Eden\Facebook\Argument;
use Eden\Facebook\Auth;
use Eden\Facebook\Base;
use Eden\Facebook\Fql\Search;
use Eden\Facebook\Fql\Select;
use Eden\Utility\Collection;
use Eden\Utility\Curl;
use Eden\Utility\Model;

/**
 * Abstractly defines a layout of available methods to
 * connect to and query a database. This class also lays out 
 * query building methods that auto renders a valid query
 * the specific database will understand without actually 
 * needing to know the query language.
 *
 * @vendor  Eden
 * @package Eden\Facebook\Fql
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Fql extends Base
{
    const SELECT = 'Select';
    const FQL_URL = 'https://graph.facebook.com/fql';
    protected $queries = array();
    protected $token = null;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Returns a collection given the query parameters
     *
     * @param string table
     * @param array filter
     * @param array sort
     * @param int start
     * @param int range
     * @return array
     */
    public function getCollection($table, $filters = null, array $sort = array(), $start = 0, $range = 0, $index = null)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string', 'array', 'null') // argument 2 must be a string numeric or null
                ->test(4, 'numeric') // argument 4 must be a numeric
                ->test(5, 'numeric') // argument 5 must be a numeric
                ->test(6, 'numeric', 'null'); // argument 6 must be a numeric or null

        $results = $this->getRows($table, $filters, $sort, $start, $range, $index);

        $collection = Collection::i();

        if (is_null($results)) {
            return $collection;
        }

        if (!is_null($index)) {
            return $this->model($results);
        }

        return $collection->set($results);
    }

    /**
     * Returns a model given the column name and the value
     *
     * @param string table
     * @param string name
     * @param string value
     * @return array|null
     */
    public function getModel($table, $name, $value)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'string', 'numeric'); // argument 3 must be a string or numeric

        $result = $this->getRow($table, $name, $value);

        $model = Model::i();

        if (is_null($result)) {
            return $model;
        }

        return $model->set($result);
    }

    /**
     * Returns a 1 row result given the column name and the value
     *
     * @param string table
     * @param string name
     * @param string value
     * @return array|null
     */
    public function getRow($table, $name, $value)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string') // argument 2 must be a string
                ->test(3, 'string', 'numeric'); // argument 3 must be a string or numeric

        $query = $this->select()
                ->from($table)
                ->where($name . ' = ' . $value)
                ->limit(0, 1);

        $results = $this->query($query);

        return isset($results[0]) ? $results[0] : null;
    }

    /**
     * Returns a list of results given the query parameters
     *
     * @param string table
     * @param array filter
     * @param array sort
     * @param int start
     * @param int range
     * @return array|null
     */
    public function getRows($table, $filters = null, array $sort = array(), $start = 0, $range = 0, $index = null)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string', 'array', 'null') // argument 2 must be a string numeric or null
                ->test(4, 'numeric') // argument 4 must be a numeric
                ->test(5, 'numeric') // argument 5 must be a numeric
                ->test(6, 'numeric', 'null'); // argument 6 must be a numeric or null

        $query = $this->select()
                ->from($table);

        if (is_array($filters)) {
            foreach ($filters as $i => $filter) {
                if (!is_array($filter)) {
                    continue;
                }

                //array('post_id=%s AND post_title IN %s', 123, array('asd'));
                $format = array_shift($filter);
                $filters[$i] = vsprintf($format, $filter);
            }
        }

        if (!is_null($filters)) {
            $query->where($filters);
        }

        if (!empty($sort)) {
            foreach ($sort as $key => $value) {
                if (is_string($key) && trim($key)) {
                    $query->sortBy($key, $value);
                }
            }
        }

        if ($range) {
            $query->limit($start, $range);
        }

        $results = $this->query($query);

        if (!is_null($index)) {
            if (empty($results)) {
                $results = null;
            } else {
                if ($index == self::FIRST) {
                    $index = 0;
                }

                if ($index == self::LAST) {
                    $index = count($results) - 1;
                }

                if (isset($results[$index])) {
                    $results = $results[$index];
                } else {
                    $results = null;
                }
            }
        }

        return $results;
    }

    /**
     * Returns the number of results given the query parameters
     *
     * @param string table
     * @param array filter
     * @return int|false
     */
    public function getRowsCount($table, $filters = null)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string', 'array', 'null'); // argument 2 must be a string, array or null

        $query = $this->select('COUNT(*)')->from($table);

        if (is_array($filters)) {
            foreach ($filters as $i => $filter) {
                if (!is_array($filter)) {
                    continue;
                }

                $format = array_shift($filter);
                $filters[$i] = vsprintf($format, $filter);
            }
        }

        if (!is_null($filters)) {
            $query->where($filters);
        }

        $results = $this->query($query);

        if (isset($results)) {
            return sizeOf($results);
        }

        return false;
    }

    /**
     * Returns the history of queries made still in memory
     * 
     * @return array|null the queries
     */
    public function getQueries($index = null)
    {
        if (is_null($index)) {
            return $this->queries;
        }

        if ($index == self::FIRST) {
            $index = 0;
        }

        if ($index == self::LAST) {
            $index = count($this->queries) - 1;
        }

        if (isset($this->queries[$index])) {
            return $this->queries[$index];
        }

        return null;
    }

    /**
     * Queries the database
     * 
     * @param string query
     * @return array|object
     */
    public function query($query)
    {
        Argument::i()->test(1, 'string', 'array', self::SELECT); // argument 1 must be a string or array

        if (!is_array($query)) {
            $query = array('q' => (string) $query);
        } else {
            foreach ($query as $key => $select) {
                $query[$key] = (string) $select;
            }

            $query = array('q' => json_encode($query));
        }

        $query['access_token'] = $this->token;
        $url = self::FQL_URL . '?' . http_build_query($query);

        $results = Curl::i()
                ->setUrl($url)
                ->setConnectTimeout(10)
                ->setFollowLocation(true)
                ->setTimeout(60)
                ->verifyPeer(false)
                ->setUserAgent(Auth::USER_AGENT)
                ->setHeaders('Expect')
                ->getJsonResponse();

        $this->queries[] = array(
            'query' => $query['q'],
            'results' => $results);

        if (isset($results['error']['message'])) {
            Exception::i()
                    ->setMessage($results['error']['message'])
                    ->trigger();
        }

        return $results['data'];
    }

    /**
     * Returns search
     *
     * @return Search
     */
    public function search()
    {
        return Search::i($this);
    }

    /**
     * Returns the select query builder
     *
     * @return Select
     */
    public function select($select = '*')
    {
        Argument::i()->test(1, 'string', 'array'); // argument 1 must be a string or array

        return Select::i($select);
    }
}