<?php
/**
 * @author VaL Doroshchuk
 * @date May 2015
 * @copyright Copyright (C) 2015 VaL Doroshchuk
 * @package protoindex
 */

namespace index\storage;

/**
 * Database storage of nodes.
 */
class Db {

    /**
     * @var PDO
     */
    protected $pdo = false;

    /**
     * @param PDO
     */
    function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * @return PDO
     */
    static function pdo() {
        $pdo = new \PDO('mysql:host=localhost;dbname=protoindex', 'root', '');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;
    }

    /**
     * Returns node that contains child tree. 
     *
     * @return Node
     */
    private static function node($path, $list) {
        $result = [];
        foreach ($list as $item) {
            $node = new \index\Node($item['path']);
            $result[$item['path']] = $node;
            if (!$item['path']) {
                continue;
            }

            $e = explode('/', $item['path']);
            unset($e[count($e) - 1]);
            $parent = implode('/', $e);
            if (isset($result[$parent])) {
                $result[$parent]->appendChild($node);
            }
        }

        return isset($result[$path]) ? $result[$path] : false;
    }

    /**
     * @return Node
     */
    function fetch($path, $depth = 0) {
        if ($path == '/') {
            $path = '';
        }
        $result = false;
        if ($depth > 0) {
            $newDepth = \index\Node::calculateDepth($path) + $depth;
            $newPath = $path . '%';

            $q = "SELECT * FROM node WHERE path like :path AND depth <= :depth";
            $stmt = $this->pdo->prepare($q);
            $stmt->bindValue(':path', $newPath);
            $stmt->bindValue(':depth', $newDepth);
        } elseif ($depth < 0) {
            $newPath = $path . '%';
            $q = "SELECT * FROM node WHERE path like :path";
            $stmt = $this->pdo->prepare($q);
            $stmt->bindValue(':path', $newPath);
        } else {
            $q = "SELECT * FROM node WHERE path=:path"; 
            $stmt = $this->pdo->prepare($q);
            $stmt->bindValue(':path', $path);
        }
        $stmt->execute();            
        $result = self::node($path, $stmt->fetchAll(\PDO::FETCH_ASSOC));

        return $result;
    }

    /**
     * Recursivelly stores node to db.
     *
     * @param Node
     * @return void
     */
    function store(\index\Node $node) {
        $q = "INSERT INTO node(path, depth) VALUES(:path, :depth)"; 
        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(':path', $node->path());
        $stmt->bindValue(':depth', $node->depth());
        $stmt->execute();

        foreach ($node->children() as $child) {
            $this->store($child);
        }
    }
}