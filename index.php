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

/**
 * Returns string with indents.
 */
function display(Node $node, $append = '') {
    $r = $append . $node->name() . "<br/>\n";
    foreach ($node->children() as $child) {
        $r .= display($child, $append . '&nbsp;&nbsp;&nbsp;&nbsp;');
    }

    return $r;
}

$db = new storage\Db(storage\Db::pdo());
$root = $db->fetch('/', -1);

$html = "<html><body>";

$html .= display($root);

$html .= "</body></html>";

echo $html;