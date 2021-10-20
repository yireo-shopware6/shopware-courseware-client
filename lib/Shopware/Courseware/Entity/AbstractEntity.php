<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

use Exception;
use Shopware\Courseware\Filesystem\JsonFile;
use Shopware\Courseware\Filesystem\MarkdownFile;
use Shopware\Courseware\Util\Context;
use Shopware\Courseware\Util\Status;

abstract class AbstractEntity
{
    protected JsonFile $jsonFile;
    protected MarkdownFile $markdownFile;
    protected Context $context;
    protected int $number;

    /** @var string[] */
    private array $markdownCharacterReplacement = ['#' => '', '_' => ''];

    public function __construct(
        JsonFile $jsonFile,
        MarkdownFile $markdownFile,
        Context $context
    ) {
        $this->jsonFile = $jsonFile;
        $this->markdownFile = $markdownFile;
        $this->context = $context;
    }

    abstract public function getMarkdown(): string;

    /**
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

    public function getTitle(): string
    {
        return $this->getJsonFile()->getTitle();
    }

    public function getMarkdownFile(): MarkdownFile
    {
        return $this->markdownFile;
    }

    public function getJsonFile(): JsonFile
    {
        return $this->jsonFile;
    }

    public function getStatus(): Status
    {
        return new Status($this->getJsonFile()->getStatus() ?? 'draft');
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getNumberPrefix(): string
    {
        return str_pad((string)$this->getNumber(), 2, '0', STR_PAD_LEFT);
    }
}
