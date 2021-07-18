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

namespace CKSource\CKFinder\Acl;

/**
 * Acl interface
 * 
 * @copyright 2015 CKSource - Frederico Knabben
 */
interface AclInterface
{
    /**
     * Allows for permission in chosen folder
     * 
     * @param string $resourceType resource type identifier (also * for all resource types)
     * @param string $folderPath   folder path
     * @param int    $permission   permission numeric value
     * @param string $role         user role name (also * for all roles)
     * 
     * @return Acl $this
     * 
     * @see Permission
     */
    public function allow($resourceType, $folderPath, $permission, $role);

    /**
     * Disallows for permission in chosen folder
     * 
     * @param string $resourceType resource type identifier (also * for all resource types)
     * @param string $folderPath   folder path
     * @param int    $permission   permission numeric value
     * @param string $role         user role name (also * for all roles)
     * 
     * @return Acl $this
     * 
     * @see Permission
     */
    public function disallow($resourceType, $folderPath, $permission, $role);

    /**
     * Checks if role has required permission for a folder
     * 
     * @param string $resourceType resource type identifier (also * for all resource types)
     * @param string $folderPath   folder path
     * @param int    $permission   permission numeric value
     * @param string $role         user role name (also * for all roles)
     * 
     * @return bool true if role has required permission
     * 
     * @see Permission
     */
    public function isAllowed($resourceType, $folderPath, $permission, $role = null);

    /**
     * Computes a mask based on current user role and ACL rules
     * 
     * @param string $resourceType resource type identifier (also * for all resource types)
     * @param string $folderPath   folder path
     * @param string $role         user role name (also * for all roles)
     * 
     * @return int computed mask value
     * 
     * @see MaskBuilder
     */
    public function getComputedMask($resourceType, $folderPath, $role = null);
}
