<?php declare(strict_types=1);
ini_set('display_errors', '1');

use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Filesystem\Reader;

require_once 'vendor/autoload.php';

$config = Config::getInstance()->setBasePath(__DIR__);
$reader = new Reader($config->getCoursewareDir());
