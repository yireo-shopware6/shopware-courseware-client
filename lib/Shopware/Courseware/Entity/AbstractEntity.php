<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

use Exception;
use Shopware\Courseware\Filesystem\JsonFile;
use Shopware\Courseware\Filesystem\MarkdownFile;

abstract class AbstractEntity
{
    /**
     * @var JsonFile
     */
    protected $jsonFile;

    /**
     * @var MarkdownFile
     */
    protected $markdownFile;

    /**
     * Course constructor.
     * @param JsonFile $jsonFile
     * @param MarkdownFile $markdownFile
     */
    public function __construct(
        JsonFile $jsonFile,
        MarkdownFile $markdownFile
    ) {
        $this->jsonFile = $jsonFile;
        $this->markdownFile = $markdownFile;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getId(): string
    {
        $id = preg_replace('/\.json$/', '', $this->getJsonFile()->getRelativePath());
        $id = preg_replace('/\/main/', '', $id);
        return $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getJsonFile()->getTitle();
    }

    /**
     * @return MarkdownFile
     */
    public function getMarkdownFile(): MarkdownFile
    {
        return $this->markdownFile;
    }

    /**
     * @return JsonFile
     */
    public function getJsonFile(): JsonFile
    {
        return $this->jsonFile;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getJsonFile()->getStatus();
    }
}
