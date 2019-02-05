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
    private $lineCoveredCount;
    /**
     * @var int
     */
    private $elementCount;
    /**
     * @var int
     */
    private $elementCoveredCount;
    /**
     * @var int
     */
    private $conditionalCount;
    /**
     * @var int
     */
    private $conditionalCoveredCount;
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
    public function setName($value): void
    {
        $this->name = $value;
    }

    /**
     * @param integer $value
     */
    public function setMethodCount($value): void
    {
        $this->methodCount = $value;
    }

    /**
     * @param string $name
     * @param int    $crap
     * @param int    $lineCount
     * @param int    $lineCoveredCount
     * @param int    $lineNumber
     */
    public function addMethod($name, $crap, $lineCount, $lineCoveredCount, $lineNumber): void
    {
        $this->method[] = array(
            'name' => $name,
            'crap' => $crap,
            'lineCount' => $lineCount,
            'lineCoveredCount' => $lineCoveredCount,
            'lineNumber' => $lineNumber
        );
    }

    /**
     * @param int $crap
     */
    public function setCrap($crap): void
    {
        $this->crap = $crap;
    }

    /**
     * @param int $lineCount
     */
    public function setLineCount($lineCount): void
    {
        $this->lineCount = $lineCount;
    }

    /**
     * @param int $lineCoveredCount
     */
    public function setLineCoveredCount($lineCoveredCount): void
    {
        $this->lineCoveredCount = $lineCoveredCount;
    }

    /**
     * @param int $elementCount
     */
    public function setElementCount($elementCount): void
    {
        $this->elementCount = $elementCount;
    }

    /**
     * @param int $elementCoveredCount
     */
    public function setElementCoveredCount($elementCoveredCount): void
    {
        $this->elementCoveredCount = $elementCoveredCount;
    }

    /**
     * @param int $conditionalCount
     */
    public function setConditionalCount($conditionalCount): void
    {
        $this->conditionalCount = $conditionalCount;
    }

    /**
     * @param int $conditionalCoveredCount
     */
    public function setConditionalCoveredCount($conditionalCoveredCount): void
    {
        $this->conditionalCoveredCount = $conditionalCoveredCount;
    }

    /**
     * @param int $value
     */
    public function setMethodCoveredCount($value): void
    {
        $this->methodCoveredCount = $value;
    }

    /**
     * @return array
     */
    public function getMethodCollection(): array
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
    public function getName(): string
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
    public function getLineCoveredCount()
    {
        return $this->lineCoveredCount;
    }

    /**
     * @return number
     */
    public function getMethodCoveredCount()
    {
        return $this->methodCoveredCount;
    }
}
