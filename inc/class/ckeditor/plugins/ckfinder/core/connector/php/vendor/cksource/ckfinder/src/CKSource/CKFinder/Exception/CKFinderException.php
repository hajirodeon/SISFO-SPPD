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

use Symfony\Component\HttpFoundation\Response;

/**
 * Base CKFinder exception class
 *
 * @copyright 2015 CKSource - Frederico Knabben
 */
class CKFinderException extends \Exception
{
    /**
     * An array of parameters passed for replacements used in translation
     *
     * @var array $parameters
     */
    protected $parameters;

    /**
     * HTTP response status code
     * @var int
     */
    protected $httpStatusCode = Response::HTTP_BAD_REQUEST;

    /**
     * Constructor
     *
     * @param string     $message    exception message
     * @param int        $code       exception code
     * @param array      $parameters parameters passed for translation
     * @param \Exception $previous   previous exception
     */
    public function __construct($message = null, $code = 0, $parameters = array(), \Exception $previous = null)
    {
        $this->parameters = $parameters;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns parameters used for replacements during translation
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return int HTTP status code for exception
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Sets HTTP status code for this exception
     * @param int $httpStatusCode
     *
     * @return $this
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;

        return $this;
    }
}
