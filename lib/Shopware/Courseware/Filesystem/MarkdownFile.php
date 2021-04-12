<?php declare(strict_types=1);

namespace Shopware\Courseware\Filesystem;

use Shopware\Courseware\Util\Config;

/**
 * Class MarkdownFile
 * @package Shopware\Courseware\Filesystem
 */
class MarkdownFile
{
    /**
     * @var string
     */
    private $file;

    /**
     * MarkdownFile constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

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
        return $this->file;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return '@todo';
    }
}