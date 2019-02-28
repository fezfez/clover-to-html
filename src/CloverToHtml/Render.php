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

class Render
{
    /**
     * @var TwigCreator
     */
    private $twigCreator;
    /**
     * @var ConfigDAO
     */
    private $configDAO;

    /**
     * Construct.
     *
     * @param TwigCreator $twig
     * @param ConfigDAO   $configDAO
     */
    public function __construct(TwigCreator $twig, ConfigDAO $configDAO)
    {
        $this->twigCreator = $twig;
        $this->configDAO   = $configDAO;
    }

    /**
     * @param Root $root
     * @param $target
     * @param $templatePath
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(Root $root, $target, $templatePath = null): void
    {
        if ($templatePath === null) {
            $templatePath = __DIR__.'/Template/';
        }

        $twig = $this->twigCreator->createInstance($templatePath);

        foreach ($root->getFileCollection() as $file) {
            $this->renderFile($file, $twig, $target, $root->getBasePath());
        }

        foreach ($root->getDirectoryCollection() as $directory) {
            $this->renderDirectory($directory, $root, $twig, $target);
            foreach ($directory->getFileCollection() as $file) {
                $this->renderFile($file, $twig, $target, $root->getBasePath());
            }
        }

        $this->copyAssets($templatePath. 'assets/', $target. '/assets');
    }

    /**
     * @param string $templatePath
     * @param string $target
     */
    private function copyAssets($templatePath, $target): void
    {
        try {
            $files = $this->configDAO->findConfig($templatePath, 'files');
            foreach ($files as $file) {
                $this->createDirIfNotExist($target.'/'.$file);
                copy($templatePath.$file, $target.'/'.$file);
            }
        } catch (\InvalidArgumentException $e) {
            // Nothing to do
        }
    }

    /**
     * @param string $path
     */
    private function createDirIfNotExist($path): void
    {
        if (!is_dir(dirname($path)) && !mkdir(dirname($path), 0777, true) && !is_dir(dirname($path))) {
            return;
        }
    }

    /**
     * @param string $base
     * @param string $actual
     *
     * @return string
     */
    private function assetsPath($base, $actual): string
    {
        return str_repeat('../', (substr_count($actual, '/') - substr_count($base, '/')) - 2);
    }

    /**
     * @param File $file
     * @param \Twig_Environment $twig
     * @param $target
     * @param $basePath
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function renderFile(File $file, \Twig_Environment $twig, $target, $basePath): void
    {
        $path = $target.'/'.$file->getDestination($basePath);

        $this->createDirIfNotExist($path);

        file_put_contents(
            $path,
            $twig->render(
                'file.twig',
                array(
                    'file' => $file,
                    'assets' => $this->assetsPath($target, $path),
                )
            )
         );
    }

    /**
     * @param Directory $directory
     * @param Root $root
     * @param \Twig_Environment $twig
     * @param $target
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function renderDirectory(Directory $directory, Root $root, \Twig_Environment $twig, $target): void
    {
        $path = $target.'/'.$directory->getDestination();

        $this->createDirIfNotExist($path);

        file_put_contents(
            $path,
            $twig->render(
                'directory.twig',
                array(
                    'directory' => $directory,
                    'directoryCollection' => $root->getAllDirIn($directory),
                    'assets' => $this->assetsPath($target, $path),
                )
            )
        );
    }
}
