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
    private $twig;
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
     * @param Root   $root
     * @param string $target
     * @param string $templatePath
     */
    public function render(Root $root, $target, $templatePath = false)
    {
        if ($templatePath === false) {
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

        $this->copyAssets($templatePath, $target);
    }

    /**
     * @param string $templatePath
     * @param string $target
     */
    private function copyAssets($templatePath, $target)
    {
        try {
            $files = $this->configDAO->findConfig($templatePath, 'files');
            foreach ($files as $file) {
                $this->createDirIfNotExist($target.'/'.$file);
                copy($templatePath.$file, $target.'/'.$file);
            }
        } catch (\InvalidArgumentException $e) {
            var_dump($e->__toString());
        }
    }

    /**
     * @param string $path
     */
    private function createDirIfNotExist($path)
    {
        if (is_dir(dirname($path)) === false) {
            mkdir(dirname($path), 0777, true);
        }
    }

    /**
     * @param string $base
     * @param string $actual
     *
     * @return string
     */
    private function assetsPath($base, $actual)
    {
        return str_repeat('../', (substr_count($actual, '/') - substr_count($base, '/')) - 1);
    }

    /**
     * @param File              $file
     * @param \Twig_Environment $twig
     * @param string            $target
     * @param string            $basePath
     */
    private function renderFile(File $file, \Twig_Environment $twig, $target, $basePath)
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
     * @param Directory         $directory
     * @param Root              $root
     * @param \Twig_Environment $twig
     * @param string            $target
     */
    private function renderDirectory(Directory $directory, Root $root, \Twig_Environment $twig, $target)
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
