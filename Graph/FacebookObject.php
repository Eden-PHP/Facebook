<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Facebook\Graph;

use Eden\Core\Exception as CoreException;
use Eden\Facebook\Argument;

/**
 * Facebook Object
 *
 * @vendor Eden
 * @package Facebook\Graph
 * @author Ian Mark Muninio <ianmuninio@openovate.com>
 */
class FacebookObject extends Base
{
    const INSTANCE = 0; // sets to multiton

    protected $myObjects = null;

    /**
     * Dynamically sets the values based on the object needs.
     *
     * @param string $name name of the method
     * @param array  $args arguments
     * @return \Eden\Facebook\Graph\FacebookObject
     * @throws \Eden\Facebook\Graph\Exception
     */
    public function __call($name, $args)
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'array');
        
        // checks if starts with set
        if (strpos($name, 'set') === 0) {
            $trueName = $name;

            // get the name of the field
            $name = substr($name, 3);
            // prepends an underscore (_) to all capital letters
            $name = preg_replace('/(?<!\ )[A-Z]/', '_$0', $name);
            $name = strtolower($name);
            $name = substr($name, 1);

            // checks if field exist from objects
            if (isset($this->myObjects[$name])) {
                $value = $this->myObjects[$name]; // gets the value
                // checks if the type is privacy
                if ($value['type'] === 'privacy') {
                    return $this->validatePrivacy($args);
                }

                // custom error message
                $message = sprintf('Facebook object %s: %s() Argument %s was '
                        . 'expecting %s, however %s was given'
                        , $this->type, $trueName, 1, $value['type'], $args[0]);

                // checks the argument
                $this->checkArgument($args[0], $value['type'], $name, 1, $message);

                // sets the new value
                $this->post[$name] = is_array($args[0]) ? implode(',', $args[0]) : $args[0];
                if ($value['type'] === 'file') {
                    $this->post[$name] = '@' . $this->post[$name];
                }

                return $this;
            } else {
                Exception::i()
                        ->setMessage(sprintf('Function %s from %s doesn\'t exist'
                                        , $name, $this->type))
                        ->trigger();
            }
        }
    }
    
    /**
     * Preloads the token, type and the arguments.
     * 
     * @param string $token
     * @param string $type
     * @param array  $objects
     * @param array  $args
     * @return void
     */
    public function __construct($token, $type, $objects, array $args = array())
    {
        Argument::i()
                ->test(1, 'string')
                ->test(2, 'string')
                ->test(3, 'array')
                ->test(4, 'array');

        $this->myObjects = $objects;

        $this->type = $type;
        parent::__construct($token); // call the parent
        
        // validate the required arguments
        $this->validateArguments($args);
    }

    /**
     * Create a facebook object.
     *
     * @return array
     */
    public function create()
    {
        return $this->getResponse();
    }

    /**
     * Checks if the arguments is valid from the required field.
     *
     * @param array $args the arguments to be checked
     * @return void
     */
    protected function validateArguments(array $args = array())
    {
        Argument::i()->test(1, 'array');
                
        // get the required fields
        $idx = 0;
        foreach ($this->myObjects as $key => $value) {
            // checks if the value of field is required
            if ($value['required']) {
                $this->checkArgument($args[$idx], $value['type'], $this->type, $idx + 1);

                $this->post[$key] = $args[$idx];

                if ($value['type'] === 'file') {
                    $this->post[$key] = '@' . $args[$idx];

                    var_dump($this->post);
                }
            }
            $idx++;
        }
    }

    /**
     * Checks the argument if matched with the type.
     *
     * @param scalar      $arg     the args
     * @param string      $type    the type to be match
     * @param string      $name    name of the facebookobject
     * @param int         $argNo   argument number from other function
     * @param string|null $message custom message if match fails
     * @return void
     * @throws \Eden\Facebook\Graph\Exception
     */
    protected function checkArgument($arg, $type, $name, $argNo, $message = null)
    {
        // check the argument 1 with try-catch block
        Argument::i()
                    ->test(2, 'string')
                    ->test(3, 'string')
                    ->test(4, 'int')
                    ->test(5, 'string', 'null');
        
        try {
            Argument::i()
                    ->test(1, $type); // just check the argument type
        } catch (CoreException $exc) {
            Exception::i()
                    ->setMessage($message ? $message :
                                    sprintf('Facebook object %s: Argument %s was '
                                            . 'expecting %s, however %s was given'
                                            , $name, $argNo, $type, $arg))
                    ->trigger();
        }
    }

    /**
     * Validates the type of facebook object privacy and encode it to json object.
     *
     * @param array $args the arguments to be encoded
     *
     * @return \Eden\Facebook\Graph\FacebookObject
     */
    protected function validatePrivacy(array $args = array())
    {
        Argument::i()->test(1, 'array');
                
        // checks if the argument 1 is not set
        if (!isset($args[0])) {
            // set the message and throws an error
            $message = sprintf('Facebook object %s: %s() Argument %s was '
                    . 'expecting %s, however %s was given'
                    , $this->type, 'setPrivacy', 1, 'array or string', 'null');

            Exception::i()
                    ->setMessage($message)
                    ->trigger();
        }

        // checks if the argument 2 is set
        // joins the arguments if is an array with comma(,) delimiter
        if (isset($args[1])) {
            // sets value to CUSTOM
            $this->post['privacy'] = json_encode(array(
                'value' => 'CUSTOM',
                'allow' => is_array($args[0]) ? implode(',', $args[0]) : $args[0],
                'deny' => is_array($args[1]) ? implode(',', $args[1]) : $args[1]
            ));
        } else {
            $this->post['privacy'] = json_encode(array(
                'value' => is_array($args[0]) ? implode(',', $args[0]) : $args[0]
            ));
        }

        return $this;
    }
}
