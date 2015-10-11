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

class Converter
{
    /**
     * @var Hydrator
     */
    private $hydrator;
    /**
     * @var Render
     */
    private $render;

    /**
     * @param Hydrator $hydrator
     * @param Render   $render
     */
    public function __construct(Hydrator $hydrator, Render $render)
    {
        $this->hydrator = $hydrator;
        $this->render   = $render;
    }

    /**
     * @param string $clover
     * @param string $target
     * @param string $templatePath
     *
     * @throws \InvalidArgumentException
     */
    public function convert($clover, $target, $templatePath = false)
    {
        if (is_file($clover) === false) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a file', $clover));
        }

        if (is_dir($target) === true) {
            throw new \InvalidArgumentException(sprintf('Target must be empty "%s"', $target));
        }

        $this->render->render(
            $this->hydrator->xmlToDto(simplexml_load_file($clover), new Root()),
            $target,
            $templatePath
        );
    }
}
