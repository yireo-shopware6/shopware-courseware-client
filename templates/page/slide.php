<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;

/** @var Reader $reader */
$id = $_GET['id'];
$type = $_GET['type'];
$entity = $reader->getEntityByIdAndType($type, $id);
$courseId = substr($id, 0, strpos($id, '/'));

$this->layout('layout/minimal', ['title' => $entity->getTitle()]);
?>
<textarea id="source"></textarea>
<script src="https://remarkjs.com/downloads/remark-latest.min.js"></script>
<script>
    var slideshow = remark.create({
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
            window.location.href='/index.php?page=lesson-overview&course_id=<?= $courseId ?>';
            return true;
        }

        return false;
        }, false);

</script>
