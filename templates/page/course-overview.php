<?php declare(strict_types=1);
use Shopware\Courseware\Filesystem\Reader;

/** @var Reader $reader */
$this->layout('layout/default', ['title' => 'Course overview']);
?>
<h1>Course overview</h1>
<table class="table table-striped">
    <thead>
    <tr>
        <th></th>
        <th>Course</th>
        <th>Slides</th>
        <th>Chapters</th>
        <th>Lessons</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($reader->getCourses() as $course): ?>
        <tr>
            <td><a href="index.php?page=slide&type=course&id=<?= $course->getId() ?>" style="text-decoration: none">▶️</a></td>
            <td><a href="index.php?page=lesson-overview&course_id=<?= $course->getId() ?>"><?= $course->getTitleWithoutMarkdown() ?></a></td>
            <td><?= $course->getSlidesNumber() ?></td>
            <td><?= count($course->getChapters()) ?></td>
            <td><?= count($course->getLessons()) ?></td>
            <td style="background-color: <?= $course->getStatus()->getColor() ?>"><?= $course->getStatus()->getLabel() ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
