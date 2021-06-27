<?php declare(strict_types=1);

namespace Shopware\Courseware\Filesystem;

use Exception;
use Shopware\Courseware\Entity\AbstractEntity;
use Shopware\Courseware\Entity\Chapter;
use Shopware\Courseware\Entity\Course;
use Shopware\Courseware\Entity\Lesson;
use Shopware\Courseware\Exception\EntityNotFoundException;

class Reader
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var MarkdownFile[]
     */
    private $markdownFiles = [];

    /**
     * @var JsonFile[]
     */
    private $jsonFiles = [];

    /**
     * @var Course[]
     */
    private $courses = [];

    /**
     * @var Chapter[]
     */
    private $chapters = [];

    /**
     * @var Lesson[]
     */
    private $lessons = [];

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
        if (!empty($this->markdownFiles)) {
            return $this->markdownFiles;
        }

        chdir($this->path);
        exec('find ' . $this->path . ' -type f -name \*.md', $files);

        foreach ($files as $file) {
            $this->markdownFiles[] = new MarkdownFile($file);
        }

        return $this->markdownFiles;
    }

    /**
     * @return JsonFile[]
     */
    public function getJsonFiles(): array
    {
        if (!empty($this->jsonFiles)) {
            return $this->jsonFiles;
        }

        chdir($this->path);
        exec('find ' . $this->path . ' -type f -name \*.json', $files);

        foreach ($files as $file) {
            $this->jsonFiles[$file] = new JsonFile($file);
        }

        ksort($this->jsonFiles);

        return $this->jsonFiles;
    }

    /**
     * @return Course[]
     */
    public function getCourses(): array
    {
        if (!empty($this->courses)) {
            return $this->courses;
        }

        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'course') {
                continue;
            }

            $this->courses[] = new Course($jsonFile, $jsonFile->getMarkdownFile());
        }

        return $this->courses;
    }

    /**
     * @param string $courseId
     * @return Course
     * @throws Exception
     */
    public function getCourseById(string $courseId): Course
    {
        foreach ($this->getCourses() as $course) {
            if ($course->getId() !== $courseId) {
                continue;
            }

            return $course;
        }

        throw new Exception('Course ID "' . $courseId . '" not found');
    }

    /**
     * @return Chapter[]
     * @throws Exception
     */
    public function getChapters(): array
    {
        if (!empty($this->chapters)) {
            return $this->chapters;
        }

        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'chapter') {
                continue;
            }

            $this->chapters[] = new Chapter($jsonFile, $jsonFile->getMarkdownFile());
        }

        return $this->chapters;
    }

    /**
     * @param string $courseId
     * @return Chapter[]
     * @throws Exception
     */
    public function getChaptersByCourseId(string $courseId): array
    {
        $chapters = [];
        foreach ($this->getChapters() as $chapter) {
            if (!strstr($chapter->getId(), $courseId)) {
                continue;
            }

            $chapters[] = $chapter;
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
        foreach ($this->getChapters() as $chapter) {
            if ($chapter->getId() !== $chapterId) {
                continue;
            }

            return $chapter;
        }

        throw new Exception('Chapter ID "' . $chapterId . '" not found');
    }

    /**
     * @return Lesson[]
     * @throws Exception
     */
    public function getLessons(): array
    {
        if (!empty($this->lessons)) {
            return $this->lessons;
        }

        $lessons = [];
        foreach ($this->getJsonFiles() as $jsonFile) {
            if ($jsonFile->getType() !== 'lesson') {
                continue;
            }

            $lessons[] = new Lesson($jsonFile, $jsonFile->getMarkdownFile());
        }

        return $lessons;
    }

    /**
     * @param string $id
     * @return Lesson[]
     * @throws Exception
     */
    public function getLessonsByIdMatch(string $id): array
    {
        $lessons = [];
        foreach ($this->getLessons() as $lesson) {
            if (!strstr($lesson->getId(), $id)) {
                continue;
            }

            $lessons[] = $lesson;
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
        foreach ($this->getLessons() as $lesson) {
            if ($lesson->getId() !== $lessonId) {
                continue;
            }

            return $lesson;
        }

        throw new Exception('Lesson ID "' . $lessonId . '" not found');
    }

    /**
     * @param string $type
     * @param string $id
     * @return AbstractEntity
     * @throws Exception
     */
    public function getEntityByIdAndType(string $type, string $id): AbstractEntity
    {
        if ($type === 'lesson') {
            return $this->getLessonById($id);
        }

        if ($type === 'chapter') {
            return $this->getChapterById($id);
        }

        if ($type === 'course') {
            return $this->getCourseById($id);
        }

        throw new EntityNotFoundException('No entity found with ID "'.$id.'" and type "'.$type.'"');
    }
}