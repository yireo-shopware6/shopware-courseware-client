<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

class Lesson extends AbstractEntity
{
    /**
     * @return string
     */
    public function getMarkdown(): string
    {
        return $this->getMarkdownFile()->getContents();
    }
}
