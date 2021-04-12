<?php declare(strict_types=1);
ini_set('display_errors', '1');

// @todo: Replace with autoloading
use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Util\Reader;

require_once __DIR__ .'/lib/Shopware/Courseware/Util/Config.php';
require_once __DIR__ .'/lib/Shopware/Courseware/Util/Reader.php';

$config = new Config(__DIR__);
$reader = new Reader($config->getCoursewareDir());