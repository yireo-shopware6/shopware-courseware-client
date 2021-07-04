<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Markdown\Parser as MarkdownParser;
use Shopware\Courseware\Util\HtmlGenerator;

/** @var Reader $reader */
$type = $_GET['type'];
$id = $_GET['id'];
$format = isset($_GET['format']) ? $_GET['format'] : 'markdown';

$entity = $reader->getEntityByIdAndType($type, $id);
$showChapterTitle = !($_ENV['SHOW_CHAPTER_OVERVIEW'] === 'false');
$showChapterOverview = !($_ENV['SHOW_CHAPTER_TITLE'] === 'false');
$allowPublishingOnly = !($_ENV['ALLOW_PUBLISHING_ONLY'] === 'false');
$content = $entity->getMarkdown($showChapterTitle, $showChapterOverview, $allowPublishingOnly);
$content = (new MarkdownParser())->parse($content);

if ($format === 'html') {
    $content = (new HtmlGenerator())->setEntity($entity)->fromMarkdown($content);
}

echo $content;
