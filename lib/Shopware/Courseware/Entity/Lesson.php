<?php declare(strict_types=1);

namespace Shopware\Courseware\Entity;

use Exception;
use Shopware\Courseware\Exception\FileNotFoundException;
use Shopware\Courseware\Filesystem\Reader;

class Lesson extends AbstractEntity
{
    /**
     * @param bool $showChapterTitle
     * @param bool $showChapterOverview
     * @param bool $allowPublishingOnly
     * @return string
     */
    public function getMarkdown(
        bool $showChapterTitle = true,
        bool $showChapterOverview = true,
        $allowPublishingOnly = true
    ): string {
        $markdown = $this->getMarkdownFile()->getContents();
        $markdown .= $this->getLabs();
        return $markdown;
    }

    /**
     * @return string[]
     */
    public function getSlides(): array
    {
        return explode('---', $this->getMarkdown());
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

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getStats(): string
    {
        $stats = [];

        $slides = $this->getSlides();
        $stats[] = count($slides) . ' slides';

        if ($this->getStudentNoteChars() > 1000) {
            $stats[] = 'With student notes';
        }

        return implode(' | ', $stats);
    }

    /**
     * @return bool
     */
    public function hasLabs(): bool
    {
        return (bool)$this->getLabs();
    }

    /**
     * @return string
     */
    public function getLabs(): string
    {
        try {
            return "---\n".file_get_contents($this->getLabsFile());
        } catch (FileNotFoundException $e) {
            return '';
        }
    }

    /**
     * @return int
     */
    public function getStudentNoteChars(): int
    {
        $studentNotesChars = 0;
        foreach ($this->getSlides() as $slide) {
            $slideParts = explode('???', $slide);
            $studentNotesChars += (isset($slideParts[1])) ? strlen(trim($slideParts[1])) : 0;
        }

        return $studentNotesChars;
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    private function getLabsFile(): string
    {
        $labsFile = dirname($this->getJsonFile()->getAbsolutePath()) . '/LABS.md';
        if (!file_exists($labsFile)) {
            throw new FileNotFoundException('Labs file "'.$labsFile.'" does not exist');
        }

        return $labsFile;
    }
}
