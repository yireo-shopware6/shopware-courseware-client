<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

use Exception;
use Shopware\Courseware\Filesystem\Reader;

class Chapter extends AbstractEntity
{
    /**
     * @return Lesson[]
     * @throws Exception
     */
    public function getLessons(): array
    {
        return Reader::getInstance()->getLessonsByIdMatch($this->getId());
    }

    /**
     * @return Lesson[]
     * @throws Exception
     */
    public function getAllowedLessons(): array
    {
        $lessons = [];
        foreach ($this->getLessons() as $lesson) {
            if (!$lesson->getStatus()->allowPublishing()) {
                continue;
            }

            $lessons[] = $lesson;
        }

        return $lessons;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getMarkdown(bool $showChapterTitle = true, bool $showChapterOverview = true): string
    {
        $markdown = $this->getMarkdownFile()->getContents();
        if (!empty($markdown)) {
            return $markdown;
        }

        if ($showChapterTitle) {
            $markdown .= "# " . $this->getTitle() . "\n";
            $markdown .= "\n---\n";
        }

        if ($showChapterOverview) {
            $markdown .= "# Chapter overview\n";
            foreach ($this->getAllowedLessons() as $lesson) {
                $markdown .= "- " . $lesson->getTitle() . "\n";
            }
        }

        // Lessons
        foreach ($this->getAllowedLessons() as $lesson) {
            $lessonMarkdown = trim($lesson->getMarkdown());
            if (empty($lessonMarkdown)) {
                continue;
            }

            $markdown .= "\n---\n";
            $markdown .= $lessonMarkdown;
        }

        return $markdown;
    }
}
