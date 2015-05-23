<?php
/**
 * @author VaL Doroshchuk
 * @date May 2015
 * @copyright Copyright (C) 2015 VaL Doroshchuk
 * @package protoindex
 */

namespace index\storage;

/**
 * Defines filesystem storage.
 */
class Fs {

    /**
     * @param Where to find entries.
     * @param Relative path.
     * @return Node
     */
    static function fetch($point, $path = '') {
        if (!file_exists($point)) {
            return false;
        }

        $node = new \index\Node($path);
        $files = is_dir($point) ? @scandir($point) : [];
        if (substr($point, -1) == '/') {
            $point = substr($point, 0, -1);
        }

        foreach ($files as $entry) {
            if ($entry == "." || $entry == "..") {
                continue;
            }

            $sub = self::fetch($point . '/' . $entry, $path . '/' . $entry);
            if ($sub) {
                $node->appendChild($sub);
            }
        }

        return $node;
    }
}