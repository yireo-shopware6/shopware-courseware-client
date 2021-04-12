<?php declare(strict_types=1);
ini_set('display_errors', '1');

use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Util\Reader;

require_once 'vendor/autoload.php';

$config = new Config(__DIR__);
$reader = new Reader($config->getCoursewareDir());
