<?php

/*
 * CKFinder
 * ========
 * http://cksource.com/ckfinder
 * Copyright (C) 2007-2015, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */

namespace CKSource\CKFinder\Authentication;

/**
 * Class CallableAuthentication
 *
 * Basic CKFinder authentication class that authenticates current user
 * using a PHP callable provided in the configuration file
 */
class CallableAuthentication implements AuthenticationInterface
{
    /**
     * @var callable
     */
    protected $authCallable;

    /**
     * @param callable $authCallable
     */
    public function __construct(callable $authCallable)
    {
        $this->authCallable = $authCallable;
    }

    /**
     * @return bool true if current user was successfully authenticated within CKFinder
     */
    public function authenticate()
    {
        return call_user_func($this->authCallable);
    }
}
