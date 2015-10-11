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

class ClassDto
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var array
     */
    private $method = array();
    /**
     * @var int
     */
    private $crap;
    /**
     * @var int
     */
    private $lineCount;
    /**
     * @var int
     */
    private $methodCount;
    /**
     * @var int
     */
    private $methodCoveredCount;

    /**
     * @param string $value
     */
    public function setName($value)
    {
        $this->name = $value;
    }

    /**
     * @param integer $value
     */
    public function setMethodCount($value)
    {
        $this->methodCount = $value;
    }

    /**
     * @param string $value
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
    }

    /**
     * @param string $name
     * @param int    $crap
     * @param int    $lineCount
     * @param int    $lineNumber
     */
    public function addMethod($name, $crap, $lineCount, $lineNumber)
    {
        $this->method[] = array(
            'name' => $name,
            'crap' => $crap,
            'lineCount' => $lineCount,
            'lineNumber' => $lineNumber
        );
    }

    /**
     * @param int $crap
     */
    public function setCrap($crap)
    {
        $this->crap = $crap;
    }

    /**
     * @param int $lineCount
     */
    public function setLineCount($lineCount)
    {
        $this->lineCount = $lineCount;
    }

    /**
     * @param int $value
     */
    public function setMethodCoveredCount($value)
    {
        $this->methodCoveredCount = $value;
    }

    /**
     * @return array
     */
    public function getMethodCollection()
    {
        return $this->method;
    }

    /**
     * @return number
     */
    public function getCrap()
    {
        return $this->crap;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return number
     */
    public function getMethodCount()
    {
        return $this->methodCount;
    }

    /**
     * @return number
     */
    public function getLineCount()
    {
        return $this->lineCount;
    }

    /**
     * @return number
     */
    public function getMethodCoveredCount()
    {
        return $this->methodCoveredCount;
    }
}
