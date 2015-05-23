<?php
/**
 * @author VaL Doroshchuk
 * @date May 2015
 * @copyright Copyright (C) 2015 VaL Doroshchuk
 * @package protoindex
 */

namespace index;

/**
 * Node of the tree.
 */
class Node {

    /**
     * @var string
     */
    protected $path = false;

    /**
     * @var int
     */
    protected $depth = false;

    /**
     * @var Node[]
     */
    protected $children = [];

    /**
     * @param Path to entry. Could be dir or file.
     */
    function __construct($path) {
        $this->path = $path;
        $this->depth = self::calculateDepth($path);
    }

    /**
     * Appends child to current node.
     */
    function appendChild(Node $node) {
        $this->children[] = $node;
    }

    /**
     * Returns children.
     */
    function children() {
        return $this->children;
    }

    /**
     * Returns name.
     */
    function name() {
        return basename($this->path);
    }

    /**
     * Returns path.
     */
    function path() {
        return $this->path;
    }

    /**
     * Returns depth.
     */
    function depth() {
        return $this->depth;
    }

    /**
     * Calculates the depth by path.
     */
    static function calculateDepth($path) {
        $e = explode('/', $path);

        return count($e) - 1;
    }

}