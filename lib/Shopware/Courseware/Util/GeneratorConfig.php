<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

class GeneratorConfig
{
    private bool $includeNotes;
    private bool $addPageBreak;
    private bool $backToIndex;
    private bool $wrapSlides;

    public function __construct(
        bool $includeNotes = false,
        bool $addPageBreak = true,
        bool $backToIndex = true,
        bool $wrapSlides = true
    ) {
        $this->includeNotes = $includeNotes;
        $this->addPageBreak = $addPageBreak;
        $this->backToIndex = $backToIndex;
        $this->wrapSlides = $wrapSlides;
    }

    public function isIncludeNotes(): bool
    {
        return $this->includeNotes;
    }

    public function isAddPageBreak(): bool
    {
        return $this->addPageBreak;
    }

    public function isBackToIndex(): bool
    {
        return $this->backToIndex;
    }

    public function isWrapSlides(): bool
    {
        return $this->wrapSlides;
    }
}
