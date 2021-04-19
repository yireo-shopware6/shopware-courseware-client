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
        return Reader::getInstance()->getLessonsByChapterId($this->getId());
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

        foreach ($this->getLessons() as $lesson) {
            if (!$lesson->getStatus()->allowPublishing()) {
                continue;
            }

            $lessonMarkdown = trim($lesson->getMarkdown());
            if ($lessonMarkdown) {
                $markdown .= $lessonMarkdown;
                $markdown .= "\n---\n";
            }
        }

        return $markdown;
    }
}
