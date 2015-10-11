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

class File
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $class = array();
    /**
     * @var array
     */
    private $lines = array();

    /**
     * @param string $value
     */
    public function setName($value)
    {
        $this->name = $value;
    }

    /**
     * @param ClassDto $value
     */
    public function addClass(ClassDto $value)
    {
        $this->class[] = $value;
    }

    /**
     * @param integer $number
     * @param string $type
     * @param boolean $isCovered
     */
    public function addLine($number, $type, $isCovered)
    {
        $this->lines[$number] = array('number' => $number, 'type' => $type, 'isCovered' => $isCovered);
    }

    /**
     * @param string $basePath
     * @return string
     */
    public function getDestination($basePath)
    {
        return dirname(str_replace($basePath, '', $this->name)).'/'.basename($this->name, '.php').'.html';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return basename($this->name);
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return basename($this->name, '.php').'.html';
    }

    /**
     * @return array
     */
    public function getClassCollection()
    {
        return $this->class;
    }

    /**
     * @return \CloverToHtml\LineIterator
     */
    public function getLineCollection()
    {
        return new LineIterator($this->name, $this->lines);
    }

    /**
     * @return array
     */
    public function getLineCoverage()
    {
        return $this->lines;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return dirname($this->name);
    }

    /**
     * @return number
     */
    public function getCountClass()
    {
        return count($this->class);
    }

    /**
     * @return number
     */
    public function getMethodCoveredCount()
    {
        $methodCovered = 0;

        foreach ($this->class as $class) {
            $methodCovered += $class->getMethodCoveredCount();
        }

        return $methodCovered;
    }

    /**
     * @return number
     */
    public function getMethodCount()
    {
        $method = 0;

        foreach ($this->class as $class) {
            $method += $class->getMethodCount();
        }

        return $method;
    }

    /**
     * @return number
     */
    public function getLineCount()
    {
        $line = 0;

        foreach ($this->class as $class) {
            $line += $class->getLineCount();
        }

        return $line;
    }
}
