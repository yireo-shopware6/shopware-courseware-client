<?php declare(strict_types=1);

namespace Shopware\Courseware\Filesystem;

use Exception;
use Shopware\Courseware\Entity\AbstractEntity;
use Shopware\Courseware\Entity\Chapter;
use Shopware\Courseware\Entity\Course;
use Shopware\Courseware\Entity\Lesson;

class Reader
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Reader
     */
    private static $instance = null;

    /**
     * @return Reader|null
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Reader();
        }

        return self::$instance;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return MarkdownFile[]
     */
    public function getMarkdownFiles(): array
    {
        chdir($this->path);
        exec('find ' . $this->path . ' -type f -name \*.md', $files);

        $markdownFiles = [];
        foreach ($files as $file) {
            $markdownFiles[] = new MarkdownFile($file);
        }

        return $markdownFiles;
    }

    /**
     * @return JsonFile[]
     */
    public function getJsonFiles(): array
    {
        chdir($this->path);
        exec('find ' . $this->path . ' -type f -name \*.json', $files);

        $jsonFiles = [];
        foreach ($files as $file) {
            $jsonFiles[$file] = new JsonFile($file);
        }

        ksort($jsonFiles);

        return $jsonFiles;
    }

    /**
     * @return Course[]
     */
    public function getCourses(): array
    {
        $courses = [];
        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'course') {
                continue;
            }

            $courses[] = new Course($jsonFile, $jsonFile->getMarkdownFile());
        }

        return $courses;
    }

    /**
     * @param string $courseId
     * @return Course
     * @throws Exception
     */
    public function getCourseById(string $courseId): Course
    {
        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'course') {
                continue;
            }

            if (!strstr($jsonFile->getRelativePath(), $courseId)) {
                continue;
            }

            return new Course($jsonFile, $jsonFile->getMarkdownFile());
        }
    }

    /**
     * @param string $courseId
     * @return Chapter[]
     * @throws Exception
     */
    public function getChaptersByCourseId(string $courseId): array
    {
        $chapters = [];
        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'chapter') {
                continue;
            }

            if (!strstr($jsonFile->getRelativePath(), $courseId)) {
                continue;
            }

            $chapters[] = new Chapter($jsonFile, $jsonFile->getMarkdownFile());
        }

        return $chapters;
    }

    /**
     * @param string $chapterId
     * @return Chapter
     * @throws Exception
     */
    public function getChapterById(string $chapterId): Chapter
    {
        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'chapter') {
                continue;
            }

            if (!strstr($jsonFile->getRelativePath(), $chapterId)) {
                continue;
            }

            return new Chapter($jsonFile, $jsonFile->getMarkdownFile());
        }
    }

    /**
     * @param string $chapterId
     * @return Lesson[]
     * @throws Exception
     */
    public function getLessonsByChapterId(string $chapterId): array
    {
        $lessons = [];
        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'lesson') {
                continue;
            }

            if (!strstr($jsonFile->getRelativePath(), $chapterId)) {
                continue;
            }

            $lessons[] = new Lesson($jsonFile, $jsonFile->getMarkdownFile());
        }

        return $lessons;
    }

    /**
     * @param string $courseId
     * @return Lesson[]
     * @throws Exception
     */
    public function getLessonsByCourseId(string $courseId): array
    {
        $lessons = [];
        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'lesson') {
                continue;
            }

            if (!strstr($jsonFile->getRelativePath(), $courseId)) {
                continue;
            }

            $lessons[] = new Lesson($jsonFile, $jsonFile->getMarkdownFile());
        }

        return $lessons;
    }

    /**
     * @param string $lessonId
     * @return Lesson
     * @throws Exception
     */
    public function getLessonById(string $lessonId): Lesson
    {
        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'lesson') {
                continue;
            }

            if (!strstr($jsonFile->getRelativePath(), $lessonId)) {
                continue;
            }

            return new Lesson($jsonFile, $jsonFile->getMarkdownFile());
        }
    }
}