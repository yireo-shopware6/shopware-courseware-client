<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

use Exception;

class Config
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * Config constructor.
     * @param string $basePath
     */
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function get(): array
    {
        $customConfigFile = $this->basePath . '/config.php';
        $defaultConfigFile = $this->basePath . '/config.php.dist';
        $configFile = (file_exists($customConfigFile)) ? $customConfigFile : $defaultConfigFile;
        $config = include($configFile);

        if (!is_dir($config['courseware_dir'])) {
            throw new Exception('Courseware directory does not exist');
        }

        return $config;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getCoursewareDir(): string
    {
        return $this->get()['courseware_dir'];
    }
}
