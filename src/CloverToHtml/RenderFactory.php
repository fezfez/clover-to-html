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

class RenderFactory
{
    /**
     * @return \CloverToHtml\Render
     */
    public static function getInstance(): Render
    {
        return new Render(TwigCreatorFactory::getInstance(), new ConfigDAO());
    }
}
