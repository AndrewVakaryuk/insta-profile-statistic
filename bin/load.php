<?php
use function StatIg\Load\loadRemoteUserData;

require_once __DIR__ . "/../vendor/autoload.php";

if (empty($argv[1])) {
    echo "Empty username argument.\n";
    echo "Usage: php bin/load.php <username>\n";
    exit(1);
}

$username = $argv[1];
$fullPath = loadRemoteUserData($username);
echo "Data has been stored to '$fullPath' \n";