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

class Stats
{
    /**
     * @return number
     */
    public function getLineCoveredPercent()
    {
        return round(($this->getLineCoverageCount() * 100) / $this->getLineCount(), 2);
    }
}
