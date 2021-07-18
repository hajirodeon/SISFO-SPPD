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

namespace CKSource\CKFinder\Exception;

use CKSource\CKFinder\Error;
use Symfony\Component\HttpFoundation\Response;

/**
 * Invalid HTTP method exception class
 *
 * Thrown when command is called using invalid HTTP method.
 *
 * @copyright 2015 CKSource - Frederico Knabben
 */
class MethodNotAllowedException extends CKFinderException
{
    protected $httpStatusCode = Response::HTTP_METHOD_NOT_ALLOWED;

    /**
     * Constructor
     *
     * @param string     $message    exception message
     * @param array      $parameters parameters passed for translation
     * @param \Exception $previous   previous exception
     */
    public function __construct($message = null, $parameters = array(), \Exception $previous = null)
    {
        parent::__construct($message, Error::INVALID_COMMAND, $parameters, $previous);
    }
}
