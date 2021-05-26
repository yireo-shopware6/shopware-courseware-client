<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Markdown\Parser;

/** @var Reader $reader */
$type = $_GET['type'];
$id = $_GET['id'];
$entity = $reader->getEntityByIdAndType($type, $id);
$showChapterTitle = !($_ENV['SHOW_CHAPTER_OVERVIEW'] === 'false');
$showChapterOverview = !($_ENV['SHOW_CHAPTER_TITLE'] === 'false');
$allowPublishingOnly = !($_ENV['ALLOW_PUBLISHING_ONLY'] === 'false');
$content = $entity->getMarkdown($showChapterTitle, $showChapterOverview, $allowPublishingOnly);
$content = (new Parser())->parse($content);

echo $content;
