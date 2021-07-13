<?php declare(strict_types=1);

use League\Plates\Engine as PlatesEngine;
use Shopware\Courseware\Filesystem\Reader;

/** @var Reader $reader */
require_once 'bootstrap.php';


$templateEngine = new PlatesEngine(__DIR__ . '/templates');
$page = $_GET['page'] ?? 'course-overview';
echo $templateEngine->render('page/' . $page, ['reader' => $reader]);
