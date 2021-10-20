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
        $i = 1;
        $lessons = [];
        foreach ($this->getLessons() as $lesson) {
            if ($this->context->isAllowPublishingOnly() && !$lesson->getStatus()->allowPublishing()) {
                continue;
            }

            $lesson->setNumber($i);
            $lessons[] = $lesson;
            $i++;
        }

        return $lessons;
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

        if ($this->context->isShowChapterOverview()) {
            $markdown .= $this->getChapterOverviewMarkdown();
        }

        // Lessons
        $i = 1;
        foreach ($this->getAllowedLessons() as $lesson) {
            $lesson->setNumber($i);
            $lessonMarkdown = $lesson->getMarkdown();
            if (empty($lessonMarkdown)) {
                continue;
            }

            $markdown .= $lessonMarkdown;
            if ($this->context->isShowChapterOverview()) {
                $markdown .= $this->getChapterOverviewMarkdown();
            }

            $i++;
        }

        return $markdown;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getChapterOverviewMarkdown(): string
    {
        $markdown = "# Lessons in \"".$this->getTitle()."\"\n";
        $markdown .= ".lessons[\n";

        foreach ($this->getAllowedLessons() as $lesson) {
            $prefix = $this->getNumber() . '.' . $lesson->getNumber();
            $markdown .= '- '.$prefix . " - " . $lesson->getTitle() . "\n";
        }

        $markdown .= "]\n";
        $markdown .= "\n---\n";

        return $markdown;
    }
}
