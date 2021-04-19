<?php declare(strict_types=1);

/** @var $title string */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $this->e($title) ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/style.css"/>
</head>
<body>
<?= $this->section('content') ?>
</body>
</html>
