<?php declare(strict_types=1);
ini_set('display_errors', '1');

use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Filesystem\Reader;
use League\Plates\Engine;

require_once 'vendor/autoload.php';

$config = Config::getInstance()->setBasePath(__DIR__);
$reader = Reader::getInstance()->setPath($config->getCoursewareDir());

$templateEngine = new Engine(__DIR__.'/templates');
echo $templateEngine->render('course-overview', ['reader' => $reader]);
