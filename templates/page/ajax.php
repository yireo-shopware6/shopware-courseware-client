<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Markdown\Parser;

/** @var Reader $reader */
$type = $_GET['type'];
$id = $_GET['id'];
$entity = $reader->getEntityByIdAndType($type, $id);
$content = $entity->getMarkdown();
$content = (new Parser())->parse($content);

echo $content;
