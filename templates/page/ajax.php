<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Parser\MarkdownParser;

/** @var Reader $reader */
$type = $_GET['type'];
$id = $_GET['id'];

if ($type === 'lesson') {
    $entity = $reader->getLessonById($id);
} elseif ($type === 'chapter') {
    $entity = $reader->getChapterById($id);
} elseif ($type === 'course') {
    $entity = $reader->getCourseById($id);
}

$content = $entity->getMarkdown();
$content = (new MarkdownParser())->parse($content);

echo $content;
