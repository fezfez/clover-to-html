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

interface StatsInterface
{
    /**
     * @return number
     */
    public function getLineCoverageCount();

    /**
     * @return number
     */
    public function getLineCount();

    /**
     * @return number
    */
    public function getLineCoveredPercent();

    /**
     * @return number
     */
    public function getCountClass();

    /**
     * @return number
     */
    public function getMethodCoveredCount();

    /**
     * @return number
     */
    public function getMethodCount();
}
