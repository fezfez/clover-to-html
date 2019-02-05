<?php

/**
 * This file is part of the Clover to Html package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CloverToHtml\Command;

use CloverToHtml\ConverterFactory;

/**
 * Convert command.
 *
 * @author Stéphane Demonchaux
 */
class ConvertCommandFactory
{
    public static function getInstance(): ConvertCommand
    {
        return new ConvertCommand(ConverterFactory::getInstance());
    }
}
