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

class Hydrator
{
    /**
     * @param \SimpleXMLElement $xml
     * @param Root              $root
     *
     * @return Root
     */
    public function xmlToDto(\SimpleXMLElement $xml, Root $root)
    {
        foreach ($xml as $data) {
            foreach ($data->package as $package) {
                $root = $this->hydrateFiles($package, $root);
            }
            $root = $this->hydrateFiles($data, $root);
        }

        $root->setBasePath($this->calculeBasePath($root));

        return $this->hydrateDirectory($root);
    }

    /**
     * @param Root $root
     *
     * @return Root
     */
    private function hydrateDirectory(Root $root)
    {
        $basePath = $root->getBasePath();

        foreach ($root->getFileCollection() as $file) {
            $dirpath = $file->getDirectory($basePath);
            if ($root->hasDirectory($dirpath)) {
                $directory = $root->getDirectoryByName($dirpath);
            } else {
                $directory = new Directory();
                $directory->setPath($dirpath);
            }

            $directory->addFile($file);

            $root->addDirectory($directory);
        }

        return $root;
    }

    /**
     * @param Root $root
     *
     * @return string
     */
    private function calculeBasePath(Root $root)
    {
        $pathCollection = array();
        foreach ($root->getFileCollection() as $file) {
            $pathCollection[] = $file->getName();
        }

        return $this->getCommonPath($pathCollection) . '/';
    }

    /**
     * @see http://rosettacode.org/wiki/Find_common_directory_path#PHP
     *
     * @param array $paths
     *
     * @return string
     */
    private function getCommonPath(array $paths)
    {
        $lastOffset = 1;
        $common     = '/';

        while (($index = strpos($paths[0], '/', $lastOffset)) !== false) {
            $dirLen = $index - $lastOffset + 1;
            $dir    = substr($paths[0], $lastOffset, $dirLen);

            foreach ($paths as $path) {
                if (substr($path, $lastOffset, $dirLen) != $dir) {
                    return $common;
                }
            }

            $common    .= $dir;
            $lastOffset = $index + 1;
        }

        return substr($common, 0, -1);
    }

    /**
     * @param \SimpleXMLElement $data
     * @param Root              $root
     *
     * @return Root
     */
    private function hydrateFiles(\SimpleXMLElement $data, Root $root)
    {
        foreach ($data->file as $fileXml) {
            $root->addFile($this->hydrateFile($fileXml));
        }

        return $root;
    }

    /**
     * @param \SimpleXMLElement $fileXml
     *
     * @return File
     */
    private function hydrateFile(\SimpleXMLElement $fileXml)
    {
        $file = new File();
        $file->setName($this->findAttributeByName($fileXml, 'name'));
        $methodNumber = 0;

        foreach ($fileXml->class as $classXml) {
            $class = new ClassDto();
            $class->setName($this->findAttributeByName($classXml, 'name'));
            $class->setMethodCount($this->findAttributeByName($classXml->metrics, 'methods'));
            $class->setLineCount($this->findAttributeByName($classXml->metrics, 'statements'));
            $class->setMethodCoveredCount($this->findAttributeByName($classXml->metrics, 'coveredmethods'));
            $class = $this->hydrateMethod($fileXml, $class, $methodNumber);

            $file->addClass($class);
        }

        foreach ($fileXml->line as $lineXml) {
            $file->addLine(
                $this->findAttributeByName($lineXml, 'num'),
                $this->findAttributeByName($lineXml, 'type'),
                (bool) $this->findAttributeByName($lineXml, 'count')
            );
        }

        return $file;
    }

    /**
     * @param \SimpleXMLElement $fileXml
     * @param ClassDto $class
     *
     * @return ClassDto
     */
    private function hydrateMethod(\SimpleXMLElement $fileXml, ClassDto $class, &$methodNumber)
    {
        foreach ($fileXml->line as $lineXml) {
            $type = $this->findAttributeByName($lineXml, 'type');

            // Must add method to class
            if ($type === 'method') {
                $class->addMethod(
                    $this->findAttributeByName($lineXml, 'name'),
                    $this->findAttributeByName($lineXml, 'crap'),
                    0,
                    $this->findAttributeByName($lineXml, 'num')
                );
                ++$methodNumber;
            }

            if ($class->getMethodCount() === $methodNumber) {
                break;
            }
        }

        return $class;
    }

    /**
     * @param \SimpleXMLElement $element
     * @param string            $name
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function findAttributeByName(\SimpleXMLElement $element, $name)
    {
        foreach ($element->attributes() as $attrName => $attValue) {
            if ($attrName === $name) {
                return (string) $attValue;
            }
        }

        throw new \InvalidArgumentException(sprintf('Attribute "%s" not found', $name));
    }
}
