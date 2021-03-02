<?php

require_once __DIR__ . "/../vendor/autoload.php";

if (empty($argv[1])) {
    echo "Empty username argument.\n";
    echo "Usage: php bin/stat.php <username>\n";
    exit(1);
}

$username = $argv[1];
$stat = \StatIg\Stat\getUserStat($username);
print_r($stat);