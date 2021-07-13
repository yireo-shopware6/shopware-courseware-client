<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

use Exception;
use Shopware\Courseware\Exception\FolderNotFoundException;

class Config
{
    /**
     * @var Config
     */
    private static $instance = null;
    /**
     * @var string
     */
    private $basePath;
    /**
     * @var array
     */
    private $data = [];

    /**
     * @return Config|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function setBasePath(string $basePath): Config
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * @throws FolderNotFoundException
     */
    public function getCoursewareDir(): string
    {
        $coursewareDir = $this->get()['courseware_dir'];
        if (!is_dir($coursewareDir)) {
            throw new FolderNotFoundException('Config value "courseware_dir" is invalid: ' . $coursewareDir);
        }

        return $coursewareDir;
    }

    public function get(): array
    {
        if (!empty($this->data)) {
            return $this->data;
        }

        $customConfigFile = $this->basePath . '/config.php';
        $defaultConfigFile = $this->basePath . '/config.php.dist';
        $configFile = (file_exists($customConfigFile)) ? $customConfigFile : $defaultConfigFile;
        $config = include($configFile);

        if (!isset($config['courseware_dir'])) {
            throw new Exception('Courseware directory is not set');
        }

        if (!is_dir($config['courseware_dir'])) {
            throw new Exception('Courseware directory does not exist');
        }

        $this->data = $config;

        return $this->data;
    }
}
