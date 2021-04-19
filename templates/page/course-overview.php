<?php declare(strict_types=1);
use Shopware\Courseware\Filesystem\Reader;

/** @var Reader $reader */
$this->layout('layout/default');
?>
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
            <td><a href="index.php?page=slide&type=course&id=<?= $course->getId() ?>"><?= $course->getTitle() ?></td>
            <td><?= count($course->getChapters()) ?></td>
            <td><a href="index.php?page=lesson-overview&course_id=<?= $course->getId() ?>"><?= count($course->getLessons()) ?></a></td>
            <td style="background-color: <?= $course->getStatus()->getColor() ?>"><?= $course->getStatus()->getLabel() ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>