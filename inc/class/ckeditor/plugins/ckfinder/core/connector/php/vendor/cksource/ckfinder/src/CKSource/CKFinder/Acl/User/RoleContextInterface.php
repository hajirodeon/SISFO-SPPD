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

namespace CKSource\CKFinder\Acl\User;

/**
 * Role context interface
 * 
 * You can implement this interface to get current user role in your application.
 * By default Acl uses SessionRoleContext to get user role from defined $_SESSION field.
 * 
 * @copyright 2015 CKSource - Frederico Knabben
 */
interface RoleContextInterface
{
    /**
     * Returns current user role name
     * 
     * @return string|null current user role name or null
     */
    public function getRole();
}
