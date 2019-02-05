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
    public function setBasePath($basePath): void
    {
        $this->basePath = $basePath;
    }

    /**
     * @param File $file
     */
    public function addFile(File $file): void
    {
        $this->files[] = $file;
    }

    /**
     * @param Directory $directory
     */
    public function addDirectory(Directory $directory): void
    {
        $this->directories[$directory->getPath()] = $directory;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @return File[]
     */
    public function getFileCollection(): array
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getDirectoryCollection(): array
    {
        return $this->directories;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasDirectory($name): bool
    {
        return isset($this->directories[$name]);
    }

    /**
     * @param string $name
     *
     * @return Directory
     */
    public function getDirectoryByName($name): Directory
    {
        return $this->directories[$name];
    }

    /**
     * @param Directory $directory
     * @return Directory[]
     */
    public function getAllDirIn(Directory $directory): array
    {
        $dirCollection  = array();
        $currentDir     = trim($directory->getPath());

        foreach ($this->directories as $path => $dir) {
            /* @var $dir Directory */
            if ($currentDir !== '' &&
                trim($path) !== $currentDir &&
                strpos(trim($path), $currentDir) === 0
                ) {
                $dirCollection[] = $dir;
            }
        }

        return $dirCollection;
    }
}
