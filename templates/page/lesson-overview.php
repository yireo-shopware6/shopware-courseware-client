<?php declare(strict_types=1);
use Shopware\Courseware\Filesystem\Reader;

/** @var Reader $reader */
$this->layout('layout/default');
$courseId = $_GET['course_id'];
$course = $reader->getCourseById($courseId);
?>
<h1>Course "<?= $course->getTitle() ?>" - Lessons</h1>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Lessons</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($course->getLessons() as $lesson): ?>
        <tr>
            <td><a href="index.php?page=slide&type=lesson&id=<?= $lesson->getId() ?>"><?= $lesson->getTitle() ?></td>
            <td style="background-color: <?= $lesson->getStatus()->getColor() ?>"><?= $lesson->getStatus()->getLabel() ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>