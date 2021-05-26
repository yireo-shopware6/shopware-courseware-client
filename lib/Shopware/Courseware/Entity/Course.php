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
     * @return string
     * @throws Exception
     */
    public function getMarkdown(): string
    {
        $markdown = $this->getMarkdownFile()->getContents();
        if (!empty($markdown)) {
            return $markdown;
        }

        // Course title
        $markdown .= "# " . $this->getTitle() . "\n";
        $markdown .= "\n---\n";

        // Course overview
        $markdown .= "# Chapters\n";
        $i = 1;
        foreach ($this->getChapters() as $chapter) {
            $markdown .= "- " . $this->getChapterPrefix($i) . " - " . $chapter->getTitle() . "\n";
            $i++;
        }
        $markdown .= "\n---\n";

        // Chapters
        $i = 1;
        foreach ($this->getChapters() as $chapter) {
            $chapterMarkdown = trim($chapter->getMarkdown(false));
            if (empty($chapterMarkdown)) {
                continue;
            }

            $markdown .= "### Chapter " . $this->getChapterPrefix($i) . "\n";
            $markdown .= "# " . $chapter->getTitle() . "\n";
            $markdown .= "\n---\n";
            $markdown .= $chapterMarkdown;
            $i++;
        }

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
