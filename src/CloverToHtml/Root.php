<?php

/**
 * This file is part of the Clover to Html package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CloverToHtml;

class Root
{
    /**
     * @var array
     */
    private $directories = array();
    /**
     * @var array
     */
    private $files = array();
    /**
     * @var string
     */
    private $basePath;

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param File $file
     */
    public function addFile(File $file)
    {
        $this->files[] = $file;
    }

    /**
     * @param Directory $directory
     */
    public function addDirectory(Directory $directory)
    {
        $this->directories[$directory->getPath()] = $directory;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @return File[]
     */
    public function getFileCollection()
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getDirectoryCollection()
    {
        return $this->directories;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasDirectory($name)
    {
        return isset($this->directories[$name]);
    }

    /**
     * @param string $name
     *
     * @return Directory
     */
    public function getDirectoryByName($name)
    {
        return $this->directories[$name];
    }
}
