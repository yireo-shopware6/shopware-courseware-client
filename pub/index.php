<?php
$url = 'https://raw.githubusercontent.com/shopware/docs/master/guides/installation/overview.md';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Title</title>
    <meta charset="utf-8">
    <link src="/css/style.css"/>
</head>
<body>
<textarea id="source"></textarea>
<script src="https://remarkjs.com/downloads/remark-latest.min.js"></script>
<script>
    var slideshow = remark.create({
        sourceUrl: '/remote.php?url=<?= $url ?>'
    });
</script>
</body>
</html>
