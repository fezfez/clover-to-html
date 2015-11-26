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

class StateManager
{
    public function getLineState(StatsInterface $stats)
    {
        $pourcent = $stats->getLineCoveredPourcent();

        if ($pourcent > 70) {
            return 'green';
        } elseif ($pourcent > 50) {
            return 'warning';
        } elseif ($pourcent > 40) {
            return 'danger';
        }
    }
}
