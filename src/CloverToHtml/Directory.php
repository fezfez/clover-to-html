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

class Directory
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var File[]
     */
    private $files = array();
    /**
     * @var Directory[]
     */
    private $directories = array();

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
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
        $this->directories[] = $directory;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->path.'index.html';
    }

    /**
     * @return array
     */
    public function getBreadcrumb()
    {
        $paths = array();

        $pathExploded = explode('/', $this->path);

        foreach ($pathExploded as $number => $dirName) {
            $paths[] = array(
                'link' => str_repeat('../', $number + 1),
                'name' => ($dirName == '') ? 'Home' : $dirName,
                'active' => ($number == count($pathExploded) - 1)
            );
        }

        return $paths;
    }

    /**
     * @return File[]
     */
    public function getFileCollection()
    {
        return $this->files;
    }

    /**
     * @return Directory[]
     */
    public function getDirectoryCollection()
    {
        return $this->directories;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->getName() . '/index.html';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return basename($this->path);
    }
}
