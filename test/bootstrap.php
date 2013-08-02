<?php

//-->
/*
 * This file is part of the Core package of the Eden PHP Library.
 * (c) 2012-2013 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

require_once __DIR__ . '/../../Core/library/Eden/Core/Loader.php';
Eden\Core\Loader::i()
        ->addRoot(true, 'Eden\\Core')
        ->register()
        ->addRoot(realpath(__DIR__ . '/../../Utility/library'), 'Eden\\Utility')
        ->register()
        ->addRoot(realpath(__DIR__ . '/../library'), 'Eden\\Facebook')
        ->register()
        ->load('Controller');