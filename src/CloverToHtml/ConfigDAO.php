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

class ConfigDAO
{
    /**
     * @param string $templatePath
     * @param string $key
     * @throws \InvalidArgumentException
     * @return array
     */
    public function findConfig($templatePath, $key = null)
    {
        if (is_file($this->getConfigPath($templatePath)) === false) {
            throw new \InvalidArgumentException(
                sprintf('Template "%s" with key "%s" not found', $templatePath, $key)
            );
        }

        if ($key === null) {
            return $config;
        }

        $config = $this->loadConfig($templatePath);

        if (isset($config[$key])) {
            return $config[$key];
        }

        throw new \InvalidArgumentException(
            sprintf('Template "%s" with key "%s" not found', $templatePath, $key)
        );
    }

    /**
     * @param string $templatePath
     * @return string
     */
    private function getConfigPath($templatePath)
    {
        return $templatePath.'/config.json';
    }

    /**
     * @param string $templatePath
     * @return array
     */
    private function loadConfig($templatePath)
    {
        return json_decode(file_get_contents($this->getConfigPath($templatePath)), true);
    }
}
