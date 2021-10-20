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
     * @return string
     * @throws Exception
     */
    public function getMarkdown(): string
    {
        $markdown = $this->getMarkdownFile()->getContents();
        if (!empty($markdown)) {
            return $markdown;
        }

        $markdown .= $this->getCourseTitleMarkdown();
        $markdown .= $this->getCourseOverviewMarkdown();

        $chapters = $this->getChapters();
        $chapterCount = count($chapters);
        foreach ($chapters as $chapter) {
            $chapterMarkdown = trim($chapter->getMarkdown());
            if (empty($chapterMarkdown)) {
                continue;
            }

            if ($this->context->isShowChapterTitle()) {
                $markdown .= "name: " . $chapter->getId() . "\n\n";
                $markdown .= "#### Chapter " . $chapter->getNumberPrefix() . "\n";
                $markdown .= "# " . $chapter->getTitle() . "\n";
                $markdown .= "\n---\n";
            }

            $markdown .= $chapterMarkdown;

            // prevent last slides to be another chapter overview + empty slide
            if($chapter->getNumber() <= $chapterCount){
                $markdown .= $this->getCourseOverviewMarkdown();
            }

        }

        return substr($markdown, 0, -4);
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

        foreach ($this->getChapters() as $chapter) {
            $markdown .= "1. " . $chapter->getTitle() . "\n";
        }

        $markdown .= "]\n";
        $markdown .= "\n---\n";
        return $markdown;
    }
}
