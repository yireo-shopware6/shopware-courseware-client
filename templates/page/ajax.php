<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Markdown\Parser as MarkdownParser;
use Shopware\Courseware\Util\HtmlGenerator;

/** @var Reader $reader */
$type = $_GET['type'];
$id = $_GET['id'];
$format = isset($_GET['format']) ? $_GET['format'] : 'markdown';

$entity = $reader->getEntityByIdAndType($type, $id);
$content = $entity->getMarkdown();
$content = (new MarkdownParser())->parse($content);

if ($format === 'html') {
    $content = (new HtmlGenerator())->setEntity($entity)->fromMarkdown($content);
}

echo $content;
