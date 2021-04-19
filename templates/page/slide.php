<?php declare(strict_types=1);
$id = $_GET['id'];
$type = $_GET['type'];
$this->layout('layout/minimal');
?>
<textarea id="source"></textarea>
<script src="https://remarkjs.com/downloads/remark-latest.min.js"></script>
<script>
    var slideshow = remark.create({
        sourceUrl: '/index.php?page=ajax&type=<?= $type ?>&id=<?= $id ?>'
    });
</script>
