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
     * @var array
     */
    private $files = array();

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
        return (($this->path === '.') ? '' : $this->path).'/index.html';
    }

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
     * @return array
     */
    public function getFileCollection()
    {
        return $this->files;
    }
}
