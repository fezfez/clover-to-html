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
    private $name;
    private $class = array();
    private $lines = array();
    private $classMethod = array();

    public function setName($value)
    {
        $this->name = $value;
    }

    public function addClass(ClassDto $value)
    {
        $this->class[] = $value;
        $this->classMethod[$value->getName()] = array($value->getMethodCount(), count($this->class));
    }

    public function addLine($number, $type, $isCovered)
    {
        $this->lines[$number] = array('number' => $number, 'type' => $type, 'isCovered' => $isCovered);
    }

    public function getDestination($basePath)
    {
        return dirname(str_replace($basePath, '', $this->name)).'/'.basename($this->name, '.php').'.html';
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFileName()
    {
        return basename($this->name);
    }

    public function getLink()
    {
        return basename($this->name, '.php').'.html';
    }

    public function getClassCollection()
    {
        return $this->class;
    }

    public function getLineCollection()
    {
        return new LineIterator($this->name, $this->lines);
    }

    public function getLineCoverage()
    {
        return $this->lines;
    }

    public function getDir()
    {
        return dirname($this->name);
    }

    public function getCountClass()
    {
        return count($this->class);
    }

    public function getMethodCoveredCount()
    {
        $methodCovered = 0;

        foreach ($this->class as $class) {
            $methodCovered += $class->getMethodCoveredCount();
        }

        return $methodCovered;
    }

    public function getMethodCount()
    {
        $method = 0;

        foreach ($this->class as $class) {
            $method += $class->getMethodCount();
        }

        return $method;
    }

    public function getLineCount()
    {
        $line = 0;

        foreach ($this->class as $class) {
            $line += $class->getLineCount();
        }

        return $line;
    }
}
