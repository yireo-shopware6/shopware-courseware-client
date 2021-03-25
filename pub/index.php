<?php
$file = 'installation/overview.md';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Title</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/style.css"/>
</head>
<body>
<textarea id="source"></textarea>
<script src="https://remarkjs.com/downloads/remark-latest.min.js"></script>
<script>
    var slideshow = remark.create({
        sourceUrl: '/ajax.php?file=<?= $file ?>'
    });
</script>
</body>
</html>
