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
    public function xmlToDto(\SimpleXMLElement $xml, Root $root): Root
    {
        foreach ($xml as $data) {
            foreach ($data->package as $package) {
                $root = $this->hydrateFiles($package, $root);
            }
            $root = $this->hydrateFiles($data, $root);
        }

        $root->setBasePath($this->calBasePath($root));

        return $this->hydrateDirectory($root);
    }

    /**
     * @param Root $root
     *
     * @return Root
     */
    private function hydrateDirectory(Root $root): Root
    {
        $basePath = $root->getBasePath();

        foreach ($root->getFileCollection() as $file) {
            $dirPath = $file->getDirectory($basePath);
            if ($root->hasDirectory($dirPath)) {
                $directory = $root->getDirectoryByName($dirPath);
            } else {
                $directory = new Directory();
                $directory->setPath($dirPath);
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
    private function calBasePath(Root $root): string
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
    private function getCommonPath(array $paths): string
    {
        $lastOffset = 1;
        $common     = '/';

        while (($index = strpos($paths[0], '/', $lastOffset)) !== false) {
            $dirLen = $index - $lastOffset + 1;
            $dir    = substr($paths[0], $lastOffset, $dirLen);

            foreach ($paths as $path) {
                if (substr($path, $lastOffset, $dirLen) !== $dir) {
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
    private function hydrateFiles(\SimpleXMLElement $data, Root $root): Root
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
    private function hydrateFile(\SimpleXMLElement $fileXml): File
    {
        $file = new File();
        $file->setName($this->findAttributeByName($fileXml, 'name'));
        $methodNumber = 0;

        foreach ($fileXml->class as $classXml) {
            $class = new ClassDto();
            $class->setName($this->findAttributeByName($classXml, 'name'));
            $class->setLineCount($this->findAttributeByName($fileXml->metrics, 'statements'));
            $class->setLineCoveredCount($this->findAttributeByName($fileXml->metrics, 'coveredstatements'));
            $class->setMethodCount($this->findAttributeByName($fileXml->metrics, 'methods'));
            $class->setMethodCoveredCount($this->findAttributeByName($fileXml->metrics, 'coveredmethods'));
            $class->setElementCount($this->findAttributeByName($fileXml->metrics, 'elements'));
            $class->setElementCoveredCount($this->findAttributeByName($fileXml->metrics, 'coveredelements'));
            $class->setConditionalCount($this->findAttributeByName($fileXml->metrics, 'conditionals'));
            $class->setConditionalCoveredCount($this->findAttributeByName($fileXml->metrics, 'coveredconditionals'));
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
     *
     * @return array
     */
    private function getMethodCoveredLines(\SimpleXMLElement $fileXml): array
    {
        $coveredLines = [];
        $totalLines = [];
        $methodName = null;
        foreach ($fileXml->line as $lineXml) {
            $type = $this->findAttributeByName($lineXml, 'type');

            // Must add method to class
            if ($type === 'method') {
                $methodName = $this->findAttributeByName($lineXml, 'name');
            }

            if ($methodName !== null && $type === 'stmt') {
                $coveredLines[$methodName] += (int) $this->findAttributeByName($lineXml, 'count');
                $totalLines[$methodName]++;
            }
        }

        return [$coveredLines, $totalLines];
    }

    /**
     * @param \SimpleXMLElement $fileXml
     * @param ClassDto $class
     * @param $methodNumber
     *
     * @return ClassDto
     */
    private function hydrateMethod(\SimpleXMLElement $fileXml, ClassDto $class, &$methodNumber): ClassDto
    {
        $methodCoveredLines = $this->getMethodCoveredLines($fileXml)[0];
        $methodLines = $this->getMethodCoveredLines($fileXml)[1];

        foreach ($fileXml->line as $lineXml) {
            $type = $this->findAttributeByName($lineXml, 'type');

            // Must add method to class
            if ($type === 'method') {
                $methodName = $this->findAttributeByName($lineXml, 'name');
                $class->addMethod(
                    $methodName,
                    $this->findAttributeByName($lineXml, 'crap'),
                    $methodLines[$methodName],
                    $methodCoveredLines[$methodName],
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
    private function findAttributeByName(\SimpleXMLElement $element, $name): string
    {
        foreach ($element->attributes() as $attrName => $attValue) {
            if ($attrName === $name) {
                return (string) $attValue;
            }
        }

        throw new \InvalidArgumentException(sprintf('Attribute "%s" not found', $name));
    }
}
