<?php
$url = $_GET['url'];
$contents = file_get_contents($url);
$contents = trim($contents);
$contents = preg_replace('/^# /msi', "\n---\n# ", $contents);
$contents = preg_replace('/^## /msi', "\n---\n## ", $contents);

echo $contents;