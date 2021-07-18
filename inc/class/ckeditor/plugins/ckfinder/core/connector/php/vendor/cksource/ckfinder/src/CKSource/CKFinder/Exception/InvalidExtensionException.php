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

/**
 * Invalid extension exception class
 *
 * Thrown when file has an invalid extension, for example if the extension
 * is not allowed for a resource type.
 *
 * @copyright 2015 CKSource - Frederico Knabben
 */
class InvalidExtensionException extends CKFinderException
{
    /**
     * Constructor
     *
     * @param string     $message    exception message
     * @param array      $parameters parameters passed for translation
     * @param \Exception $previous   previous exception
     */
    public function __construct($message = 'Invalid extension', $parameters = array(), \Exception $previous = null)
    {
        parent::__construct($message, Error::INVALID_EXTENSION, $parameters, $previous);
    }
}
