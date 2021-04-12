<?php declare(strict_types=1);

namespace Shopware\Courseware\Filesystem;

use Exception;
use Shopware\Courseware\Util\Config;

/**
 * Class AbstractFile
 * @package Shopware\Courseware\Filesystem
 */
abstract class AbstractFile
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * JsonFile constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isValidFilename(): bool
    {
        if (!preg_match('/([^a-zA-Z0-9\.\/\-]+)/', $this->getRelativePath())) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getRelativePath():string
    {
        $basePath = Config::getInstance()->getCoursewareDir();
        return str_replace($basePath . '/', '', $this->getAbsolutePath());
    }

    /**
     * @return string
     */
    public function getAbsolutePath(): string
    {
        return $this->filename;
    }
}