<?php

/**
 * This file is part of the CloverToHtml package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CloverToHtml\Service;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use CloverToHtml\Command\ConvertCommandFactory;

/**
 * Create CLI instance.
 *
 * @author Stéphane Demonchaux
 */
class CliFactory
{
    /**
     * Create CLI instance.
     *
     * @return Application
     */
    public static function getInstance()
    {
        $questionHelper = new QuestionHelper();
        $application = new Application('CloverToHtml Command Line Interface', 'Beta 0.1.0');
        $application->getHelperSet()->set(new FormatterHelper(), 'formatter');
        $application->getHelperSet()->set($questionHelper, 'question');

        $application->add(ConvertCommandFactory::getInstance());

        return $application;
    }
}
