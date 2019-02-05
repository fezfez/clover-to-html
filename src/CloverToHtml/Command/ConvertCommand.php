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

use CloverToHtml\Converter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Convert command.
 *
 * @author Stéphane Demonchaux
 */
class ConvertCommand extends Command
{
    /**
     * @var Converter
     */
    private $engine;

    /**
     * @param Converter $engine
     */
    public function __construct(Converter $engine)
    {
        $this->engine = $engine;
        parent::__construct();
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure(): void
    {
        $this
            ->setName('cloverToHtml:convert')
            ->setDescription('Convert a clover.xml to html')
            ->addArgument('source', InputArgument::REQUIRED, 'clover.xml')
            ->addArgument('target', InputArgument::REQUIRED, 'target')
            ->addOption('template', null, null, 'Custom template path');
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->engine->convert(
            $input->getArgument('source'),
            $input->getArgument('target'),
            $input->getOption('template') ?: null
        );

        $output->writeLn(sprintf('Coverage generated in %s', $input->getArgument('target')));
    }
}
