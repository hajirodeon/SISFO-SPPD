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

namespace CKSource\CKFinder\Backend;

use CKSource\CKFinder\Acl\AclInterface;
use CKSource\CKFinder\Acl\Permission;
use CKSource\CKFinder\Backend\Adapter\AwsS3;
use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Config;
use CKSource\CKFinder\Filesystem\Path;
use CKSource\CKFinder\ResizedImage\ResizedImage;
use CKSource\CKFinder\ResourceType\ResourceType;
use CKSource\CKFinder\Utils;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Plugin\GetWithMetadata;
use League\Flysystem\Filesystem;

/**
 * Backend filesystem class
 *
 * Wrapper class for League\Flysystem\Filesystem with
 * CKFinder customizations
 */
class Backend extends Filesystem
{
    /**
     * CKFinder app container
     *
     * @var CKFinder $app
     */
    protected $app;

    /**
     * Acl
     *
     * @var AclInterface $acl
     */
    protected $acl;

    /**
     * Config
     *
     * @var Config $ckConfig
     */
    protected $ckConfig;

    /**
     * Backend configuration array
     */
    protected $backendConfig;

    /**
     * Constructor
     *
     * @param array            $backendConfig    backend configuration node
     * @param CKFinder         $app              CKFinder app container
     * @param AdapterInterface $adapter          adapter
     * @param array|null       $filesystemConfig config
     */
    public function __construct(array $backendConfig, CKFinder $app, AdapterInterface $adapter, $filesystemConfig = null)
    {
        $this->app = $app;
        $this->backendConfig = $backendConfig;
        $this->acl = $app['acl'];
        $this->ckConfig = $app['config'];

        parent::__construct($adapter, $filesystemConfig);

        $this->addPlugin(new GetWithMetadata());
    }

    /**
     * Returns the name of the backend
     *
     * @return string name of the backend
     */
    public function getName()
    {
        return $this->backendConfig['name'];
    }

    /**
     * Returns an array of commands that should use operation tracking
     *
     * @return array
     */
    public function getTrackedOperations()
    {
        return isset($this->backendConfig['trackedOperations']) ? $this->backendConfig['trackedOperations'] : array();
    }

    /**
     * Returns a path based on resource type and resource type relative path
     *
     * @param ResourceType $resourceType resource type
     * @param string       $path         resource type relative path
     *
     * @return string path to be used with backend adapter
     */
    public function buildPath(ResourceType $resourceType, $path)
    {
        return Path::combine($resourceType->getDirectory(), $path);
    }

    /**
     * Returns a filtered list of directories for given resource type and path
     *
     * @param ResourceType $resourceType
     * @param string       $path
     * @param bool         $recursive
     *
     * @return array
     */
    public function directories(ResourceType $resourceType, $path = '', $recursive = false)
    {
        $directoryPath = $this->buildPath($resourceType, $path);
        $contents = $this->listContents($directoryPath, $recursive);

        foreach ($contents as &$entry) {
            $entry['acl'] = $this->acl->getComputedMask($resourceType->getName(), Path::combine($path, $entry['basename']));
        }

        return array_filter($contents, function ($v) {
            return isset($v['type']) &&
                   $v['type'] === 'dir' &&
                   !$this->isHiddenFolder($v['basename']) &&
                   $v['acl'] & Permission::FOLDER_VIEW;
        });
    }

    /**
     * Returns a filtered list of files for given resource type and path
     *
     * @param ResourceType $resourceType
     * @param string       $path
     * @param bool         $recursive
     *
     * @return array
     */
    public function files(ResourceType $resourceType, $path = '', $recursive = false)
    {
        $directoryPath = $this->buildPath($resourceType, $path);
        $contents = $this->listContents($directoryPath, $recursive);

        return array_filter($contents, function ($v) use ($resourceType) {
            return isset($v['type']) &&
                   $v['type'] === 'file' &&
                   !$this->isHiddenFile($v['basename']) &&
                   $resourceType->isAllowedExtension(isset($v['extension']) ? $v['extension'] : '');
        });
    }

    /**
     * Check if directory under given path contains subdirectories
     *
     * @param ResourceType $resourceType
     * @param string       $path
     *
     * @return bool true if directory contains subdirectories
     */
    public function containsDirectories(ResourceType $resourceType, $path = '')
    {
        $baseAdapter = $this->getBaseAdapter();
        if (method_exists($baseAdapter, 'containsDirectories')) {
            return $baseAdapter->containsDirectories($this, $resourceType, $path, $this->acl);
        }

        $directoryPath = $this->buildPath($resourceType, $path);
        $contents = $this->listContents($directoryPath);

        foreach ($contents as $entry) {
            if ($entry['type'] === 'dir' &&
                !$this->isHiddenFolder($entry['basename']) &&
                $this->acl->isAllowed($resourceType->getName(), Path::combine($path, $entry['basename']), Permission::FOLDER_VIEW)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if file with given name is hidden
     *
     * @param string $fileName
     *
     * @return bool true if file is hidden
     */
    public function isHiddenFile($fileName)
    {
        $hideFilesRegex = $this->ckConfig->getHideFilesRegex();

        if ($hideFilesRegex) {
            return (bool) preg_match($hideFilesRegex, $fileName);
        }

        return false;
    }

    /**
     * Check if directory with given name is hidden
     *
     * @param string $folderName
     *
     * @return bool true if directory is hidden
     */
    public function isHiddenFolder($folderName)
    {
        $hideFoldersRegex = $this->ckConfig->getHideFoldersRegex();

        if ($hideFoldersRegex) {
            return (bool) preg_match($hideFoldersRegex, $folderName);
        }

        return false;
    }

    /**
     * Check if path is hidden
     *
     * @param string $path
     *
     * @return bool true if path is hidden
     */
    public function isHiddenPath($path)
    {
        $pathParts = explode('/', trim($path, '/'));
        if ($pathParts) {
            foreach ($pathParts as $part) {
                if ($this->isHiddenFolder($part)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Delete a directory
     *
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($dirname)
    {
        $baseAdapter = $this->getBaseAdapter();

        // For FTP first remove recursively all directory contents
        if ($baseAdapter instanceof Ftp) {
            $this->deleteContents($dirname);
        }

        return parent::deleteDir($dirname);
    }

    /**
     * Delete all contents in given directory
     *
     * @param string $dirname
     */
    public function deleteContents($dirname)
    {
        $contents = $this->listContents($dirname);

        foreach ($contents as $entry) {
            if ($entry['type'] === 'dir') {
                $this->deleteContents($entry['path']);
                $this->deleteDir($entry['path']);
            } else {
                $this->delete($entry['path']);
            }
        }
    }

    /**
     * Checks if backend contains a directory
     *
     * The Backend::has() method is not always reliable and may
     * work differently for various adapters. Checking for directory
     * should be done with this method.
     *
     * @param string $directoryPath
     *
     * @return bool
     */
    public function hasDirectory($directoryPath)
    {
        $pathParts = array_filter(explode('/', $directoryPath), 'strlen');
        $dirName = array_pop($pathParts);
        $contents = $this->listContents(implode('/', $pathParts));

        foreach ($contents as $c) {
            if (isset($c['type']) && isset($c['basename']) && $c['type'] === 'dir' && $c['basename'] === $dirName) {
                return true;
            }
        }
    }

    /**
     * Returns an URL to a file.
     *
     * If useProxyCommand option is set for a backend, the returned
     * URL will point to CKFinder connector Proxy command.
     *
     * @param ResourceType $resourceType      file resource type
     * @param string       $folderPath        resource-type relative folder path
     * @param string       $fileName          file name
     * @param string|null  $thumbnailFileName thumbnail file name - if file is a thumbnail
     *
     * @return string|null URL to a file or null if backend do not support it
     */
    public function getFileUrl(ResourceType $resourceType, $folderPath, $fileName, $thumbnailFileName = null)
    {
        if (isset($this->backendConfig['useProxyCommand'])) {
            $connectorUrl = $this->app->getConnectorUrl();

            $queryParameters = array(
                'command' => 'Proxy',
                'type' => $resourceType->getName(),
                'currentFolder' => $folderPath,
                'fileName' => $fileName
            );

            if ($thumbnailFileName) {
                $queryParameters['thumbnail'] = $thumbnailFileName;
            }

            $proxyCacheLifetime = (int) $this->ckConfig->get('cache.proxyCommand');

            if ($proxyCacheLifetime > 0) {
                $queryParameters['cache'] = $proxyCacheLifetime;
            }

            return $connectorUrl . '?' . http_build_query($queryParameters, '', '&');
        }

        $path = $thumbnailFileName
            ? Path::combine($resourceType->getDirectory(), $folderPath, ResizedImage::DIR, $fileName, $thumbnailFileName)
            : Path::combine($resourceType->getDirectory(), $folderPath, $fileName);

        if (isset($this->backendConfig['baseUrl'])) {
            return Path::combine($this->backendConfig['baseUrl'], Utils::encodeURLParts($path));
        }

        $baseAdapter = $this->getBaseAdapter();

        if (method_exists($baseAdapter, 'getFileUrl')) {
            return $baseAdapter->getFileUrl($path);
        }

        return null;
    }

    /**
     * Returns the base url used to build direct url to files stored
     * in this backend
     *
     * @return string|null base url or null if base url for a backend
     *                     was not defined
     */
    public function getBaseUrl()
    {
        if (isset($this->backendConfig['baseUrl']) && !$this->usesProxyCommand()) {
            return $this->backendConfig['baseUrl'];
        }

        return null;
    }

    /**
     * Returns the root directory defined for backend
     *
     * @return string|null root directory or null if root directory
     *                     was not defined
     */
    public function getRootDirectory()
    {
        if (isset($this->backendConfig['root'])) {
            return $this->backendConfig['root'];
        }

        return null;
    }

    /**
     * Returns a bool value telling if backend uses the Proxy command
     *
     * @return bool
     */
    public function usesProxyCommand()
    {
        return isset($this->backendConfig['useProxyCommand']) && $this->backendConfig['useProxyCommand'];
    }

    /**
     * Creates a stream for writing
     *
     * @param string $path file path
     *
     * @return resource|null a stream to a file or null if backend doesn't
     *                       support writing streams
     */
    public function createWriteStream($path)
    {
        $baseAdapter = $this->getBaseAdapter();

        if (method_exists($baseAdapter, 'createWriteStream')) {
            return $baseAdapter->createWriteStream($path);
        }

        return null;
    }

    /**
     * Renames object under given path
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool true on success, false on failure.
     */
    public function rename($path, $newpath)
    {
        $baseAdapter = $this->getBaseAdapter();

        if ($baseAdapter instanceof AwsS3 && $this->hasDirectory($path)) {
            return $baseAdapter->renameDirectory($path, $newpath);
        }

        return parent::rename($path, $newpath);
    }

    /**
     * Returns a base adapter used by this backend.
     *
     * The used adapter might be decorated with CachedAdapter. In this
     * case the returned adapter is the internal one used by CachedAdapter.
     *
     * @return AdapterInterface
     */
    public function getBaseAdapter()
    {
        if ($this->adapter instanceof CachedAdapter) {
            return $this->adapter->getAdapter();
        }

        return $this->adapter;
    }
}
