<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

class GeneratorConfig
{
    private bool $includeNotes;
    private bool $addPageBreak;
    private bool $backToIndex;
    private bool $wrapSlides;

    /**
     * Config constructor.
     * @param bool $includeNotes
     * @param bool $addPageBreak
     * @param bool $backToIndex
     */
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

    /**
     * @return bool
     */
    public function isIncludeNotes(): bool
    {
        return $this->includeNotes;
    }

    /**
     * @return bool
     */
    public function isAddPageBreak(): bool
    {
        return $this->addPageBreak;
    }

    /**
     * @return bool
     */
    public function isBackToIndex(): bool
    {
        return $this->backToIndex;
    }

    /**
     * @return bool
     */
    public function isWrapSlides(): bool
    {
        return $this->wrapSlides;
    }
}