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
 * Access denied exception
 *
 * Thrown when file system permissions do not allow to perform an operation,
 * like accessing a directory or writing a file.
 *
 * @copyright 2015 CKSource - Frederico Knabben
 */
class AccessDeniedException extends CKFinderException
{
    protected $httpStatusCode = Response::HTTP_FORBIDDEN;

    /**
     * Constructor
     *
     * @param string     $message    exception message
     * @param array      $parameters parameters passed for translation
     * @param \Exception $previous   previous exception
     */
    public function __construct($message = 'Access denied', $parameters = array(), \Exception $previous = null)
    {
        parent::__construct($message, Error::ACCESS_DENIED, $parameters, $previous);
    }
}
