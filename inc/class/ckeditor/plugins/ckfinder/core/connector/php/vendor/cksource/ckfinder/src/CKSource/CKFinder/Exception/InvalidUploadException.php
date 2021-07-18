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
 * Invalid upload exception class
 *
 * @copyright 2015 CKSource - Frederico Knabben
 */
class InvalidUploadException extends CKFinderException
{
    /**
     * Constructor
     *
     * @param string     $message    exception message
     * @param int        $code       exception code
     * @param array      $parameters parameters passed for translation
     * @param \Exception $previous   previous exception
     */
    public function __construct($message = 'Invalid upload', $code = Error::UPLOADED_INVALID, $parameters = array(), \Exception $previous = null)
    {
        parent::__construct($message, $code, $parameters, $previous);
    }
}
