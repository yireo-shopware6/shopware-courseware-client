<?php
include_once __DIR__ . '/../app.php';
?>
<html>
<body>
<ul>
    <?php foreach ($reader->getFiles() as $file): ?>
    <li><?= $file ?></li>
    <?php endforeach ?>
</ul>
</body>
</html>
