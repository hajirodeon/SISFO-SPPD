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
use CKSource\CKFinder\Filesystem\File\UploadedFile;

/**
 * FileUploadEvent event class
 */
class FileUploadEvent extends CKFinderEvent
{
    /**
     * @var UploadedFile $uploadedFile
     */
    protected $uploadedFile;

    /**
     * Constructor
     *
     * @param CKFinder     $app
     * @param UploadedFile $uploadedFile
     */
    public function __construct(CKFinder $app, UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;

        parent::__construct($app);
    }

    /**
     * Returns uploaded file object
     *
     * @return UploadedFile
     *
     * @deprecated Please use getFile() instead
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Returns uploaded file object
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->uploadedFile;
    }
}
