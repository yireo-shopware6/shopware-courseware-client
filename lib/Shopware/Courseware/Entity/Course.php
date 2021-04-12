<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

use Shopware\Courseware\Filesystem\Reader;

class Course extends AbstractEntity
{
    /**
     * @return Chapter[]
     */
    public function getChapters(): array
    {
        return Reader::getInstance()->getChaptersByCourseId($this->getId());
    }

    /**
     * @return Lesson[]
     */
    public function getLessons(): array
    {
        return Reader::getInstance()->getLessonsByCourseId($this->getId());
    }
}
