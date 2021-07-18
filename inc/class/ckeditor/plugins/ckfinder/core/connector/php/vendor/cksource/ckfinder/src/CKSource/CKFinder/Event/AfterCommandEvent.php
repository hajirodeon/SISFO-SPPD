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

namespace CKSource\CKFinder\Event;

use CKSource\CKFinder\CKFinder;
use Symfony\Component\HttpFoundation\Response;

/**
 * BeforeCommandEvent event class
 */
class AfterCommandEvent extends CKFinderEvent
{
    /**
     * Command name
     *
     * @var string $commandObject
     */
    protected $commandName;

    /**
     * Response object received from a command
     *
     * @var Response $response
     */
    protected $response;

    /**
     * Constructor
     *
     * @param CKFinder $app
     * @param string   $commandName
     * @param Response $response
     */
    public function __construct(CKFinder $app, $commandName, Response $response)
    {
        $this->commandName = $commandName;
        $this->response = $response;

        parent::__construct($app);
    }

    /**
     * Returns the response object received from a command
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets the response to be returned
     *
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
