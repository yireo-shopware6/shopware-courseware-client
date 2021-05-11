<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Util\Status;

/** @var Reader $reader */
$this->layout('layout/default', ['title' => 'Lesson overview']);
$courseId = $_GET['course_id'];
$currentStatus = $_GET['status'] ?? '';
$excludedStatuses = isset($_GET['excludedStatuses']) ? explode(',', $_GET['excludedStatuses']) : [];
$course = $reader->getCourseById($courseId);
?>
<h1>Lessons of "<?= $course->getTitle() ?>"</h1>

<form method="get">
    <input type="hidden" name="course_id" value="<?= $courseId ?>"/>
    <input type="hidden" name="page" value="lesson-overview"/>
    <select name="status" onChange="this.form.submit()">
        <option>-- select --</option>
        <?php foreach (Status::ALLOWED_STATUTES as $allowedStatusCode => $allowedStatus): ?>
            <option value="<?= $allowedStatusCode ?>"><?= $allowedStatusCode ?></option>
        <?php endforeach; ?>
    </select>
</form>

<table class="table table-striped">
    <thead>
    <tr>
        <th>Lesson</th>
        <th>Course</th>
        <th>Chapter</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($course->getLessons() as $lesson): ?>
        <?php if ($currentStatus && $currentStatus !== (string)$lesson->getStatus()) {
            continue;
        } ?>
        <?php if (in_array((string)$lesson->getStatus(), $excludedStatuses)) {
            continue;
        } ?>
        <tr>
            <td><a href="index.php?page=slide&type=lesson&id=<?= $lesson->getId() ?>"><?= $lesson->getTitle() ?></a><br>
            <small><?= $lesson->getChapterHierarchy() ?></small></td>
            <td><?= $lesson->getCourse()->getTitle() ?></td>
            <td><?= $lesson->getChapter()->getTitle() ?></td>
            <td style="background-color: <?= $lesson->getStatus()->getColor() ?>"><?= $lesson->getStatus()->getLabel() ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
