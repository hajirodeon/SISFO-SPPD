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

use CKSource\CKFinder\Acl\User\RoleContextInterface;
use CKSource\CKFinder\Filesystem\Path;

/**
 * Acl class
 * 
 * @copyright 2015 CKSource - Frederico Knabben
 */
class Acl implements AclInterface
{
    /**
     * @brief List of Acl entries
     * 
     * A list of array entries in following form:
     * <pre>[folderPath][role][resourceType] => MaskBuilder</pre>
     * 
     * @var array $entries
     */
    protected $rules = array();

    /**
     * @brief Role context interface
     * 
     * By default an instance of SessionRoleContext is used as a role context.
     * You can easily add a new class that implements RoleContextInterface to
     * better fit your application.
     * 
     * @var RoleContextInterface $roleContext
     */
    protected $roleContext = null;

    /**
     * @brief Cache for computed masks
     *
     * This array contains computed masks results to avoid double checks
     * for the same path
     *
     * @var array $cachedResults
     */
    protected $cachedResults = array();

    /**
     * Constructor
     * 
     * @param RoleContextInterface $roleContext
     */
    public function __construct(RoleContextInterface $roleContext)
    {
        $this->roleContext = $roleContext;
    }

    /**
     * Sets rules for Acl using config nodes
     * 
     * It's assumed that Acl config nodes used here have following form:
     *
     * @code
     * array(
     *      'role'          => 'foo',
     *      'resourceType'  => 'Images',
     *      'folder'        => '/bar',
     * 
     *      // Permissions
     *      'FOLDER_VIEW'   => true,
     *      'FOLDER_CREATE' => true,
     *      'FOLDER_RENAME' => true,
     *      'FOLDER_DELETE' => true,
     * 
     *      'FILE_VIEW'     => true,
     *      'FILE_CREATE'   => true,
     *      'FILE_RENAME'   => true,
     *      'FILE_DELETE'   => true
     * )
     * @endcode
     * 
     * In case if any permission is missing it's inherited from the parent folder
     *
     * @param array $aclConfigNodes Acl config nodes
     *
     */
    public function setRules($aclConfigNodes)
    {
        foreach ($aclConfigNodes as $node) {
            $role = isset($node['role']) ? $node['role'] : "*";

            $resourceType = isset($node['resourceType']) ? $node['resourceType'] : "*";

            $folder = isset($node['folder']) ? $node['folder'] : "/";

            $permissions = Permission::getAll();

            foreach ($permissions as $permissionName => $permissionValue) {
                if (isset($node[$permissionName])) {
                    $allow = (bool) $node[$permissionName];

                    if ($allow) {
                        $this->allow($resourceType, $folder, $permissionValue, $role);
                    } else {
                        $this->disallow($resourceType, $folder, $permissionValue, $role);
                    }
                }
            }
        }
    }

    /**
     * Allows for permission for given role
     *
     * @param string $resourceType
     * @param string $folderPath
     * @param int    $permission
     * @param string $role
     *
     * @return $this|Acl
     */
    public function allow($resourceType, $folderPath, $permission, $role)
    {
        $folderPath = Path::normalize($folderPath);

        if (!isset($this->rules[$folderPath][$role][$resourceType])) {
            $this->rules[$folderPath][$role][$resourceType] = new MaskBuilder();
        }

        /* @var $ruleMask MaskBuilder */
        $ruleMask = $this->rules[$folderPath][$role][$resourceType];

        $ruleMask->allow($permission);

        return $this;
    }

    /**
     * Disallows for permission for given role
     *
     * @param string $resourceType
     * @param string $folderPath
     * @param int    $permission
     * @param string $role
     *
     * @return $this|Acl
     */
    public function disallow($resourceType, $folderPath, $permission, $role)
    {
        $folderPath = Path::normalize($folderPath);

        if (!isset($this->rules[$folderPath][$role][$resourceType])) {
            $this->rules[$folderPath][$role][$resourceType] = new MaskBuilder();
        }

        /* @var $ruleMask MaskBuilder */
        $ruleMask = $this->rules[$folderPath][$role][$resourceType];

        $ruleMask->disallow($permission);

        return $this;
    }

    /**
     * Checks if given role has a permission
     *
     * @param string      $resourceType
     * @param string      $folderPath
     * @param int         $permission
     * @param string|null $role
     *
     * @return bool
     */
    public function isAllowed($resourceType, $folderPath, $permission, $role = null)
    {
        $mask = $this->getComputedMask($resourceType, $folderPath, $role);

        return ($mask & $permission) === $permission;
    }

    /**
     * Returns computed mask
     *
     * @param string      $resourceType
     * @param string      $folderPath
     * @param string|null $role
     *
     * @return int
     */
    public function getComputedMask($resourceType, $folderPath, $role = null)
    {
        $computedMask = 0;

        $role = $role ?: $this->roleContext->getRole();

        $folderPath = trim($folderPath, "/");

        if (isset($this->cachedResults[$resourceType][$folderPath])) {
            return $this->cachedResults[$resourceType][$folderPath];
        }

        $pathParts = explode("/", $folderPath);

        $currentPath = "/";

        $pathPartsCount = count($pathParts);

        for ($i = -1; $i < $pathPartsCount; $i++) {
            if ($i >= 0) {
                if (!strlen($pathParts[$i])) {
                    continue;
                }

                if (array_key_exists($currentPath . '*/', $this->rules)) {
                    $computedMask = $this->mergePathComputedMask($computedMask, $resourceType, $role, $currentPath . '*/');
                }

                $currentPath .= $pathParts[$i] . '/';
            }

            if (array_key_exists($currentPath, $this->rules)) {
                $computedMask = $this->mergePathComputedMask($computedMask, $resourceType, $role, $currentPath);
            }
        }

        $this->cachedResults[$resourceType][$folderPath] = $computedMask;

        return $computedMask;
    }

    /**
     * Merges permissions masks to allow permissions inheritance from parent folders
     * 
     * @param int    $currentMask  current mask numeric value
     * @param string $resourceType resource type identifier
     * @param string $role         user role name
     * @param string $folderPath   folder path
     * 
     * @return int computed mask numeric value
     */
    protected function mergePathComputedMask($currentMask, $resourceType, $role, $folderPath)
    {
        $folderRules = $this->rules[$folderPath];

        $possibleRules = array(
            array('*', '*'),
            array('*', $resourceType),
            array($role, '*'),
            array($role, $resourceType),
        );

        foreach ($possibleRules as $rule) {
            list($role, $resourceType) = $rule;

            if (isset($folderRules[$role][$resourceType])) {
                /* @var $ruleMask MaskBuilder */
                $ruleMask = $folderRules[$role][$resourceType];

                $currentMask = $ruleMask->mergeRules($currentMask);
            }
        }

        return $currentMask;
    }
}
