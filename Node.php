<?php
/**
 * @author VaL Doroshchuk
 * @date May 2015
 * @copyright Copyright (C) 2015 VaL Doroshchuk
 * @package protoindex
 */

namespace index;

class Node {

    public $path = false;
    public $depth = false;

    public $children = [];

    function __construct($path) {
        $this->path = $path;
        $this->depth = self::calculateDepth($path);
    }

    function appendChild(Node $node) {
        $this->children[] = $node;
    }

    function children() {
        return $this->children;
    }

    function name() {
        return basename($this->path);
    }

    static function calculateDepth($path) {
        $e = explode('/', $path);

        return count($e) - 1;
    }

}