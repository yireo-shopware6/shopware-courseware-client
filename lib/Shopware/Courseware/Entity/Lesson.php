<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

use Exception;
use Shopware\Courseware\Filesystem\Reader;

class Lesson extends AbstractEntity
{
    /**
     * @return string
     */
    public function getMarkdown(): string
    {
        return $this->getMarkdownFile()->getContents();
    }

    /**
     * @return Course
     * @throws Exception
     */
    public function getCourse(): Course
    {
        $idParts = explode('/', $this->getId());
        return Reader::getInstance()->getCourseById($idParts[0]);
    }

    /**
     * @return Chapter
     * @throws Exception
     */
    public function getChapter(): Chapter
    {
        $idParts = explode('/', $this->getId());
        return Reader::getInstance()->getChapterById($idParts[0] . '/' . $idParts[1]);
    }

    public function getChapterHierarchy(): string
    {
        try {
            $jsonFilePath = $this->getJsonFile()->getRelativePath();
        } catch (Exception $e) {
            return '';
        }

        $firstSlash = strpos($jsonFilePath, '/');
        $lastSlash = strrpos($jsonFilePath, '/');
        $pathLength = $lastSlash - $firstSlash - 1;

        $jsonFilePathSegment = substr($jsonFilePath, $firstSlash + 1, $pathLength);

        return str_replace('/', ' : ', $jsonFilePathSegment);
    }
}
