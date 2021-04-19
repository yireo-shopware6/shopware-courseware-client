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
        return Reader::getInstance()->getLessonsByCourseId($this->getId());
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

        foreach ($this->getChapters() as $chapter) {
            $chapterMarkdown = trim($chapter->getMarkdown());
            if ($chapterMarkdown) {
                $markdown .= $chapterMarkdown;
                $markdown .= "\n---\n";
            }
        }


        return $markdown;
    }
}
