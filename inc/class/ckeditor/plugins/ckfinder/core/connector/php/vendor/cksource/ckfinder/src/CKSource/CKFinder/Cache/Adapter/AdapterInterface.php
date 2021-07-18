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

namespace CKSource\CKFinder\Cache\Adapter;

/**
 * Interface AdapterInterface
 */
interface AdapterInterface
{
    /**
     * Sets the value in cache under given key
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool true if successful
     */
    public function set($key, $value);

    /**
     * Returns value under given key from cache
     *
     * @param string $key
     *
     * @return array
     */
    public function get($key);

    /**
     * Deletes value under given key  from cache
     *
     * @param string $key
     *
     * @return bool true if successful
     */
    public function delete($key);

    /**
     * Deletes all cache entries with given key prefix
     *
     * @param string $keyPrefix
     */
    public function deleteByPrefix($keyPrefix);

    /**
     * Changes prefix for all entries given key prefix
     *
     * @param string $sourcePrefix
     * @param string $targetPrefix
     */
    public function changePrefix($sourcePrefix, $targetPrefix);
}
