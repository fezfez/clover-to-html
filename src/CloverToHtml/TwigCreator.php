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

class TwigCreator
{
    /**
     * @var ConfigDAO
     */
    private $configDAO;

    /**
     * @param ConfigDAO $configDAO
     */
    public function __construct(ConfigDAO $configDAO)
    {
        $this->configDAO = $configDAO;
    }

    /**
     * @param string $templatePath
     * @return \Twig_Environment
     */
    public function createInstance($templatePath)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($templatePath));

        try {
            $extension = $this->configDAO->findConfig($templatePath, 'extension');
            foreach ($extension as $extensionName => $extension) {
                $twig->addGlobal($extensionName, new $extension);
            }
        } catch (\InvalidArgumentException $e) {
            // Nothing to do
        }

        return $twig;
    }
}
