<?php
/** @var \Shopware\Courseware\Filesystem\Reader $reader */
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Course overview</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Course</th>
            <th>Chapters</th>
            <th>Lessons</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reader->getCourses() as $course): ?>
            <tr>
                <td><a href="slide.php?type=course&id=<?= $course->getId() ?>"><?= $course->getTitle() ?></td>
                <td><?= count($course->getChapters()) ?></td>
                <td><?= count($course->getLessons()) ?></td>
                <td><?= $course->getStatus() ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>