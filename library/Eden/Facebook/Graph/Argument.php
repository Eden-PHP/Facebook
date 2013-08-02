<?php

//-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Facebook\Graph;

use Eden\Facebook\Argument as FacebookArgument;

/**
 * Facebook Errors
 *
 * @vendor  Eden
 * @package Eden\Facebook\Graph
 * @author  Christian Blanquera <cblanquera@openovate.com>
 * @since   3.0.0
 */
class Argument extends FacebookArgument
{
    const AUTHENTICATION_FAILED = 'Application authentication failed. Facebook returned %s: %s';
    const GRAPH_FAILED = 'Call to graph.facebook.com failed. Facebook returned %s: %s';
    const REQUIRES_AUTH = 'Call to %s requires authentication. Please set token first or set argument 4 in setObject() to false.';
}