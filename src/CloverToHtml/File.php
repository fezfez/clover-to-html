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

class File extends Stats implements StatsInterface
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
    public function getDestination($basePath): string
    {
        return $this->getDirectory($basePath) . basename($this->name, '.php').'.html';
    }

    /**
     * @param string $basePath
     * @return string
     */
    public function getDirectory($basePath): string
    {
        $dirname = dirname(str_replace($basePath, '', $this->name));
        return ($dirname === '.' ? '' : $dirname . '/');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return basename($this->name);
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return basename($this->name, '.php').'.html';
    }

    /**
     * @return array
     */
    public function getClassCollection(): array
    {
        return $this->class;
    }

    /**
     * @return \CloverToHtml\LineIterator
     */
    public function getLineCollection(): LineIterator
    {
        return new LineIterator($this->name, $this->lines);
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return dirname($this->name);
    }

    /**
     * @return array
     */
    public function getLineCoverage(): array
    {
        return $this->lines;
    }

    /**
     * @return number
     */
    public function getLineCoverageCount()
    {
        $lineCovered = 0;

        foreach ($this->lines as $line) {
            if ($line['isCovered'] === true) {
                ++$lineCovered;
            }
        }

        return $lineCovered;
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
