<?php
/**
 * @author VaL Doroshchuk
 * @date May 2015
 * @copyright Copyright (C) 2015 VaL Doroshchuk
 * @package protoindex
 */

namespace index;

include_once "Node.php";
include_once "storages/Fs.php";
include_once "storages/Db.php";

$argv = $_SERVER['argv'];
$dir = isset($argv[1]) ? $argv[1] : false;
if (!$dir)  {
    echo "$ " . $argv[0] . " [DIR]\n";
    exit;
}

$root = storage\Fs::fetch($dir);
if (!$root) {
    echo "Could not find files in '$dir'\n";
    exit;
}

$db = new storage\Db(storage\Db::pdo());
if ($db->store($root)) {
    echo "Success\n";
}
