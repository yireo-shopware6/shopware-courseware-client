<?php declare(strict_types=1);

use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Util\Status;

/** @var Reader $reader */
$this->layout('layout/default', ['title' => 'Lesson overview']);

$oldTitle='';

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
        <option value="">-- select --</option>
        <?php foreach (Status::ALLOWED_STATUTES as $allowedStatusCode => $allowedStatus): ?>
            <option value="<?= $allowedStatusCode ?>" <?= ($currentStatus === $allowedStatusCode ? 'selected' : '') ?>><?= $allowedStatusCode ?></option>
        <?php endforeach; ?>
    </select>
</form>

<table class="table table-striped">
    <thead>
    <tr>
        <th width="20%">Chapter</th>
        <th>Lesson</th>
        <th>Labs</th>
        <th width="10%">Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($course->getLessons() as $lesson): ?>
        <?php
        if (
                ($currentStatus && $currentStatus !== (string) $lesson->getStatus()) ||
                (in_array((string) $lesson->getStatus(), $excludedStatuses, true))
        ) {
            continue;
        }

        $chapter = $lesson->getChapter();
        $currentChapterTitle = $chapterTitle = $chapter->getTitle();
        $chapterTitle = $oldTitle !== $currentChapterTitle? $currentChapterTitle : '';
        $oldTitle = $currentChapterTitle;
        ?>

        <tr>
            <td>
                <a href="/index.php?page=slide&type=chapter&id=<?= $chapter->getId() ?>"><?= $chapterTitle ?></a>
            </td>
            <td>
                <a href="/index.php?page=slide&type=lesson&id=<?= $lesson->getId() ?>"><?= $lesson->getTitle() ?></a><br>
                <span style="font-size:50%"><?= $lesson->getChapterHierarchy() ?> | <?= $lesson->getStats() ?></span>
            </td>
            <td>
                <?= $lesson->hasLabs() ? 'X': '' ?>
            </td>
            <td style="background-color: <?= $lesson->getStatus()->getColor() ?>">
                <?= $lesson->getStatus()->getLabel() ?>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
