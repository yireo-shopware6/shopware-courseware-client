<?php

include_once __DIR__ . '/../app.php';
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>
<body>
<div class="container">
<table class="table table-striped">
    <thead>
    <tr>
        <th>Lesson</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($reader->getMarkdownFiles() as $file): ?>
        <tr>
            <td><a href="slide.php?file=<?= $file->getAbsolutePath() ?>"><?= $file->getRelativePath() ?></td>
            <td><?= $file->getStatus() ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
</div>
</body>
</html>
