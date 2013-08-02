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

use Eden\Core\Exception as CoreException;
use Eden\Facebook\Argument;
use Eden\Facebook\Base;
use Eden\Utility\Collection;
use Eden\Utility\Type\StringType;

/**
 * Facebook Search
 *
 * @vendor  Eden
 * @package Eden\Facebook\Fql
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   1.0.0
 */
class Search extends Base
{
    const ASC = 'ASC';
    const DESC = 'DESC';
    protected $database = null;
    protected $table = null;
    protected $columns = array();
    protected $filter = array();
    protected $sort = array();
    protected $start = 0;
    protected $range = 0;
    protected $group = array();

    /**
     * Preload the database
     * 
     * @param Fql $database
     */
    public function __construct(Fql $database)
    {
        $this->database = $database;
    }

    /**
     * Magic method calling for this class
     * 
     * @param type $name
     * @param type $args
     * @return Search
     */
    public function __call($name, $args)
    {
        if (strpos($name, 'filterBy') === 0) {
            //filterByUserName('Chris', '-')
            $separator = '_';
            if (isset($args[1]) && is_scalar($args[1])) {
                $separator = (string) $args[1];
            }

            $key = StringType::i($name)
                    ->substr(8)
                    ->preg_replace("/([A-Z])/", $separator . "$1")
                    ->substr(strlen($separator))
                    ->strtolower()
                    ->get();

            if (!isset($args[0])) {
                $args[0] = null;
            }

            $key = $key . '=%s';

            $this->addFilter($key, $args[0]);

            return $this;
        }

        if (strpos($name, 'sortBy') === 0) {
            //filterByUserName('Chris', '-')
            $separator = '_';
            if (isset($args[1]) && is_scalar($args[1])) {
                $separator = (string) $args[1];
            }

            $key = StringType::i($name)
                    ->substr(6)
                    ->preg_replace("/([A-Z])/", $separator . "$1")
                    ->substr(strlen($separator))
                    ->strtolower()
                    ->get();

            if (!isset($args[0])) {
                $args[0] = self::ASC;
            }

            $this->addSort($key, $args[0]);

            return $this;
        }

        try {
            return parent::__call($name, $args);
        } catch (CoreException $e) {
            Exception::i()
                    ->setMessage($e->getMessage())
                    ->trigger();
        }
    }

    /**
     * Adds filter
     * 
     * @param string
     * @param string[,string..]
     * @return this
     */
    public function addFilter()
    {
        Argument::i()->test(1, 'string'); // argument 1 must be a string

        $this->filter[] = func_get_args();

        return $this;
    }

    /**
     * Adds sort
     * 
     * @param string
     * @param string
     * @return this
     */
    public function addSort($column, $order = self::ASC)
    {
        Argument::i()
                ->test(1, 'string') // argument 1 must be a string
                ->test(2, 'string'); // argument 2 must be a string

        if ($order != self::DESC) {
            $order = self::ASC;
        }

        $this->sort[$column] = $order;

        return $this;
    }

    /**
     * Returns the results in a collection
     *
     * @return Collection
     */
    public function getCollection($key = 'last')
    {
        $rows = $this->getRows($key);

        if (count($this->group) == 1) {
            return Collection::i($rows);
        }

        foreach ($rows as $key => $collection) {
            $rows[$key] = Collection::i($collection['fql_result_set']);
        }

        return $rows;
    }

    /**
     * Returns the array rows
     *
     * @return array
     */
    public function getRows($key = 'last')
    {
        $this->group($key);

        if (empty($this->group)) {
            return array();
        }

        $group = array();
        foreach ($this->group as $key => $query) {
            $this->table = $query['table'];
            $this->columns = $query['columns'];
            $this->filter = $query['filter'];
            $this->sort = $query['sort'];
            $this->start = $query['start'];
            $this->range = $query['range'];

            $query = $this->getQuery();

            if (!empty($this->columns)) {
                $query->select(implode(', ', $this->columns));
            }

            foreach ($this->sort as $name => $value) {
                $query->sortBy($name, $value);
            }

            if ($this->range) {
                $query->limit($this->start, $this->range);
            }

            $group[$key] = $query;
        }

        $query = $group;

        if (count($query) == 1) {
            $query = $group[$key];
        }

        $results = $this->database->query($query);
        return $results;
    }

    /**
     * Returns the total results
     *
     * @return int
     */
    public function getTotal()
    {
        $query = $this->getQuery()->select('COUNT(*)');

        $rows = $this->database->query($query);

        if (isset($rows)) {
            return sizeOf($rows);
        }

        return false;
    }

    /**
     * Stores this search and resets class.
     * Useful for multiple queries.
     *
     * @param scalar
     * @return this
     */
    public function group($key)
    {
        Argument::i()->test(1, 'scalar');
        if (is_null($this->table)) {
            return $this;
        }

        $this->group[$key] = array(
            'table' => $this->table,
            'columns' => $this->columns,
            'filter' => $this->filter,
            'sort' => $this->sort,
            'start' => $this->start,
            'range' => $this->range);

        $this->table = null;
        $this->columns = array();
        $this->filter = array();
        $this->sort = array();
        $this->start = 0;
        $this->range = 0;

        return $this;
    }

    /**
     * Sets Columns
     * 
     * @param string[,string..]|array
     * @return this
     */
    public function setColumns($columns)
    {
        if (!is_array($columns)) {
            $columns = func_get_args();
        }

        $this->columns = $columns;

        return $this;
    }

    /**
     * Sets the pagination page
     *
     * @param int
     * @return this
     */
    public function setPage($page)
    {
        Argument::i()->test(1, 'int'); // argument 1 must be an integer

        if ($page < 1) {
            $page = 1;
        }

        $this->start = ($page - 1) * $this->range;

        return $this;
    }

    /**
     * Sets the pagination range
     *
     * @param int
     * @return this
     */
    public function setRange($range)
    {
        Argument::i()->test(1, 'int'); // argument 1 must be an integer

        if ($range < 0) {
            $range = 25;
        }

        $this->range = $range;

        return $this;
    }

    /**
     * Sets the pagination start
     *
     * @param int
     * @return this
     */
    public function setStart($start)
    {
        Argument::i()->test(1, 'int'); // argument 1 must be an integer

        if ($start < 0) {
            $start = 0;
        }

        $this->start = $start;

        return $this;
    }

    /**
     * Sets Table
     * 
     * @param string
     * @return this
     */
    public function setTable($table)
    {
        Argument::i()->test(1, 'string'); // argument 1 must be an string
        $this->table = $table;
        return $this;
    }

    /**
     * Returns the complete select statement
     * 
     * @return Select
     */
    protected function getQuery()
    {
        $query = $this->database->select()->from($this->table);

        foreach ($this->filter as $i => $filter) {
            //array('post_id=%s AND post_title IN %s', 123, array('asd'));
            $where = array_shift($filter);
            if (!empty($filter)) {
                foreach ($filter as $i => $value) {
                    if (!is_string($value)) {
                        continue;
                    }
                    $filter[$i] = "'" . $value . "'";
                }
                $where = vsprintf($where, $filter);
            }

            $query->where($where);
        }

        return $query;
    }
}