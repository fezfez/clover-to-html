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
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Construct.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Root   $root
     * @param string $target
     */
    public function render(Root $root, $target, $templatePath = false)
    {
        if ($templatePath === false) {
            $templatePath = __DIR__.'/Template/';
        }

        if (($this->twig->getLoader() instanceof \Twig_Loader_Filesystem) === false) {
            throw new \InvalidArgumentException(
                sprintf('Twig loader "%s" not supported', get_class($this->twig->getLoader()))
            );
        }

        $this->twig->getLoader()->setPaths($templatePath);

        foreach ($root->getFileCollection() as $file) {
            $this->renderFile($file, $target, $root->getBasePath());
        }

        foreach ($root->getDirectoryCollection() as $directory) {
            $this->renderDirectory($directory, $target);
            foreach ($directory->getFileCollection() as $file) {
                $this->renderFile($file, $target, $root->getBasePath());
            }
        }

        $configFile = $templatePath.'/config.json';

        if (is_file($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);

            foreach ($config['files'] as $file) {
                if (basename($file) === '*') {
                } else {
                    $this->createDirIfNotExist($target.'/'.$file);
                    copy($templatePath.$file, $target.'/'.$file);
                }
            }
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
     * @param File   $file
     * @param string $target
     * @param string $basePath
     */
    private function renderFile(File $file, $target, $basePath)
    {
        $path = $target.'/'.$file->getDestination($basePath);

        $this->createDirIfNotExist($path);

        file_put_contents(
            $path,
            $this->twig->render(
                'file.twig',
                array(
                    'file' => $file,
                    'assets' => $this->assetsPath($target, $path),
                )
            )
         );
    }

    /**
     * @param string $base
     * @param string $actual
     *
     * @return string
     */
    private function assetsPath($base, $actual)
    {
        return str_repeat('../', (substr_count($actual, '/') - substr_count($base, '/')) - 2);
    }

    /**
     * @param Directory $directory
     * @param string    $target
     * @param string    $basePath
     */
    private function renderDirectory(Directory $directory, $target)
    {
        $path = $target.'/'.$directory->getDestination();

        $this->createDirIfNotExist($path);

        file_put_contents(
            $path,
            $this->twig->render(
                'directory.twig',
                array(
                    'directory' => $directory,
                    'assets' => $this->assetsPath($target, $path),
                )
            )
        );
    }
}
