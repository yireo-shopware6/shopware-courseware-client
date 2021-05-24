<?php declare(strict_types=1);
ini_set('display_errors', '1');

use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Filesystem\Reader;

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = Config::getInstance()->setBasePath(__DIR__);
$reader = Reader::getInstance()->setPath($config->getCoursewareDir());
