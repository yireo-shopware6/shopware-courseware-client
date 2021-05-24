<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

use Exception;
use Shopware\Courseware\Exception\FolderNotFoundException;

class Config
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var Config
     */
    private static $instance = null;

    /**
     * @return Config|null
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Config();
        }

        return self::$instance;
    }

    /**
     * @param string $basePath
     * @return $this
     */
    public function setBasePath(string $basePath): Config
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function get(): array
    {
        if (!empty($this->data)) {
            return $this->data;
        }

        $customConfigFile = $this->basePath . '/config.php';
        $defaultConfigFile = $this->basePath . '/config.php.dist';
        $configFile = (file_exists($customConfigFile)) ? $customConfigFile : $defaultConfigFile;
        $config = include($configFile);

        if (!is_dir($config['courseware_dir'])) {
            throw new Exception('Courseware directory does not exist');
        }

        $this->data = $config;
        return $this->data;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getCoursewareDir(): string
    {
        $coursewareDir = $this->get()['courseware_dir'];
        if (!is_dir($coursewareDir)) {
            throw new FolderNotFoundException('Config value "courseware_dir" is invalid: ' . $coursewareDir);
        }

        return $coursewareDir;
    }
}
