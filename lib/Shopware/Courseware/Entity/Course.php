<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

use Exception;
use Shopware\Courseware\Filesystem\Reader;

class Course extends AbstractEntity
{
    /**
     * @return Chapter[]
     * @throws Exception
     */
    public function getChapters(): array
    {
        return Reader::getInstance()->getChaptersByCourseId($this->getId());
    }

    /**
     * @return Lesson[]
     * @throws Exception
     */
    public function getLessons(): array
    {
        return Reader::getInstance()->getLessonsByIdMatch($this->getId());
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getSlidesNumber(): int
    {
        $markdownParts = explode('---', $this->getMarkdown());
        return count($markdownParts);
    }

    /**
     * @param bool $showChapterTitle
     * @param bool $showChapterOverview
     * @param bool $allowPublishingOnly
     * @return string
     * @throws Exception
     */
    public function getMarkdown(
        bool $showChapterTitle = true,
        bool $showChapterOverview = true,
        $allowPublishingOnly = true
    ): string {
        $markdown = $this->getMarkdownFile()->getContents();
        if (!empty($markdown)) {
            return $markdown;
        }

        $markdown .= $this->getCourseTitleMarkdown();
        $markdown .= $this->getCourseOverviewMarkdown();

        // Chapters
        $i = 1;
        foreach ($this->getChapters() as $chapter) {
            $chapterMarkdown = trim($chapter->getMarkdown(false));
            if (empty($chapterMarkdown)) {
                continue;
            }

            $markdown .= "#### Chapter " . $this->getChapterPrefix($i) . "\n";
            $markdown .= "# " . $chapter->getTitle() . "\n";
            $markdown .= "\n---\n";
            $markdown .= $chapterMarkdown;
            $i++;
        }

        return $markdown;
    }

    /**
     * @return string
     */
    private function getCourseTitleMarkdown(): string
    {
        $markdown = "# " . $this->getTitle() . "\n";
        $markdown .= "\n---\n";
        return $markdown;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getCourseOverviewMarkdown(): string
    {
        $markdown = "# Chapters\n";
        $markdown .= ".chapters[\n";
        $i = 1;
        foreach ($this->getChapters() as $chapter) {
            $markdown .= "1. " . $chapter->getTitle() . "\n";
            $i++;
        }

        $markdown .= "]\n";
        $markdown .= "\n---\n";
        return $markdown;
    }

    /**
     * @param int $number
     * @return string
     */
    private function getChapterPrefix(int $number): string
    {
        return str_pad((string)$number, 2, '0', STR_PAD_LEFT);
    }
}
