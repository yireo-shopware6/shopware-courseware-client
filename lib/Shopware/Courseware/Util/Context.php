<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

class Context
{
    private bool $showChapterTitle = true;
    private bool $showChapterOverview = true;
    private bool $allowPublishingOnly = true;

    public static function create(): Context
    {
        $context = new Context();
        $context->setAllowPublishingOnly((bool)$_ENV['ALLOW_PUBLISHING_ONLY']);
        $context->setShowChapterOverview((bool)$_ENV['SHOW_CHAPTER_OVERVIEW']);
        $context->setShowChapterTitle((bool)$_ENV['SHOW_CHAPTER_TITLE']);
        return $context;
    }

    /**
     * @return bool
     */
    public function isShowChapterTitle(): bool
    {
        return $this->showChapterTitle;
    }

    /**
     * @param bool $showChapterTitle
     */
    public function setShowChapterTitle(bool $showChapterTitle): void
    {
        $this->showChapterTitle = $showChapterTitle;
    }

    /**
     * @return bool
     */
    public function isShowChapterOverview(): bool
    {
        return $this->showChapterOverview;
    }

    /**
     * @param bool $showChapterOverview
     */
    public function setShowChapterOverview(bool $showChapterOverview): void
    {
        $this->showChapterOverview = $showChapterOverview;
    }

    /**
     * @return bool
     */
    public function isAllowPublishingOnly(): bool
    {
        return $this->allowPublishingOnly;
    }

    /**
     * @param bool $allowPublishingOnly
     */
    public function setAllowPublishingOnly(bool $allowPublishingOnly): void
    {
        $this->allowPublishingOnly = $allowPublishingOnly;
    }
}