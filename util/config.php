<?php
$customConfigFile = dirname(__DIR__) . '/config.php';
$defaultConfigFile = dirname(__DIR__) . '/config.php.dist';
$configFile = (file_exists($customConfigFile)) ? $customConfigFile : $defaultConfigFile;
$config = include($configFile);

if (is_dir($config['courseware_dir'])) {
    throw new Exception('Courseware directory does not exist');
}

return $config;
