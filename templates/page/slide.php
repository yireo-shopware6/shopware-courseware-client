<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;

/** @var Reader $reader */
$id = $_GET['id'];
$type = $_GET['type'];
$entity = $reader->getEntityByIdAndType($type, $id);
$courseId = substr($id, 0, (int)strpos($id, '/'));

$backlink = '';

if($type !== 'course'){
    $backlink = '?page=lesson-overview&course_id=' . $courseId;
}

$this->layout('layout/minimal', ['title' => $entity->getTitleWithoutMarkdown()]);
?>
<textarea id="source"></textarea>
<script src="https://remarkjs.com/downloads/remark-latest.min.js"></script>
<script>
    var slideshow = remark.create({
        ratio: '16:9',
        countIncrementalSlides: false,
        navigation: {
            scroll: <?= $_ENV['REMARK_SCROLL'] ?? 'false' ?>,
            touch: <?= $_ENV['REMARK_TOUCH'] ?? 'true' ?>,
            click: <?= $_ENV['REMARK_CLICK'] ?? 'true' ?>,
        },
        highlightStyle: 'github-gist',
        sourceUrl: '/index.php?page=ajax&type=<?= $type ?>&id=<?= $id ?>'
    });
    window.addEventListener("keyup", function(e){
        if(e.code === 'Escape') {
            window.location.href='<?= '/index.php' . $backlink ?>';
            return true;
        }

        return false;
        }, false);

</script>
