<?php

//-->
/*
 * This file is part of the Core package of the Eden PHP Library.
 * (c) 2012-2013 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

require_once __DIR__ . '/../../Core/Loader.php';
Eden\Core\Loader::i()
        ->addRoot(true, 'Eden\\Core')
        ->addRoot(realpath(__DIR__ . '/../..'), 'Eden\\Oauth')
        ->addRoot(realpath(__DIR__ . '/../..'), 'Eden\\Curl')
        ->addRoot(realpath(__DIR__ . '/../..'), 'Eden\\Collection')
        ->addRoot(realpath(__DIR__ . '/../..'), 'Eden\\Model')
        ->addRoot(realpath(__DIR__ . '/../..'), 'Eden\\Type')
        ->register()
        ->load('Controller');