<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

use Exception;
use Shopware\Courseware\Filesystem\JsonFile;
use Shopware\Courseware\Filesystem\MarkdownFile;
use Shopware\Courseware\Util\Status;

abstract class AbstractEntity
{
    /**
     * @var JsonFile
     */
    protected JsonFile $jsonFile;

    /**
     * @var MarkdownFile
     */
    protected MarkdownFile $markdownFile;

    /** @var string[] */
    private array $markdownCharacterReplacement = ['#' => '', '_' => ''];

    /**
     * @return string
     */
    abstract public function getMarkdown(bool $showChapterTitle = true, bool $showChapterOverview = true, $allowPublishingOnly = true): string;



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

    public function getTitleWithoutMarkdown(): string
    {
        $title = $this->getTitle();

        return strtr($title, $this->markdownCharacterReplacement);

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
     * @return Status
     */
    public function getStatus(): Status
    {
        return new Status($this->getJsonFile()->getStatus() ?? 'draft');
    }
}
