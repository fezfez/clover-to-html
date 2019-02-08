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

class Directory extends Stats implements StatsInterface
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
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->path.'index.html';
    }

    /**
     * @return array
     */
    public function getBreadcrumb(): array
    {
        $paths = array();

        $pathExploded = explode('/', $this->path);

        foreach ($pathExploded as $number => $dirName) {
            if ($number === count($pathExploded) - 1) {
                continue;
            }

            $repeat = count($pathExploded) - $number - 2;
            if ($repeat < 0) {
                $repeat = 0;
            }

            $paths[] = array(
                'link' => str_repeat('../', $repeat) . 'index.html',
                'name' => (trim($dirName) === '') ? '.' : $dirName,
                'active' => $number === (count($pathExploded) - 2)
            );
        }

        return $paths;
    }

    /**
     * @return File[]
     */
    public function getFileCollection(): array
    {
        return $this->files;
    }

    /**
     * @return Directory[]
     */
    public function getDirectoryCollection(): array
    {
        return $this->directories;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->getName() . '/index.html';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return basename($this->path);
    }

    /**
     * @return number
     */
    public function getLineCount()
    {
        return $this->countMagic('getLineCount');
    }

    /**
     * @return number
     */
    public function getLineCoverageCount()
    {
        return $this->countMagic('getLineCoverageCount');
    }

    /**
     * @return number
     */
    public function getCountClass()
    {
        return $this->countMagic('getCountClass');
    }

    /**
     * @return number
     */
    public function getMethodCoveredCount()
    {
        return $this->countMagic('getMethodCoveredCount');
    }

    /**
     * @return number
     */
    public function getMethodCount()
    {
        return $this->countMagic('getMethodCount');
    }

    /**
     * @param string $method
     * @return number
     */
    private function countMagic($method)
    {
        $line = 0;

        foreach ($this->files as $file) {
            $line += $file->$method();
        }

        return $line;
    }
}
