# protoindex

Creating navigation trees usually depends on requirements.
For example how long the tree should be kept, how big is it, how often the tree should be updated, what is the purpose of it etc.

The most easiest way to store a tree it is just simple sql table with name of entry(file or dir) and its parent id.
In this case traversing is also not quite hard:

1. Fetch root node and after that fetch its children (or at the same query).
2. Go through children and fetch their children.

I tried to go a bit further and introduce depth to nodes, and now it is possible to fetch nodes within specific depth to requested.


    $root = (new storage\Db(storage\Db::pdo()))->fetch('assets', 3);
    
Which will return root with its children no deeper than 3.

    $root = (new storage\Db(storage\Db::pdo()))->fetch('', -1);
    
Which will return root node of full tree.

# Design

Node class does not know how it should be stored and parsed.
Class Fs parses and fetches nodes from filesystem
Db parses and fetches nodes from db. Also could store them.

It is important to understand that in case if subtree is extremely big > 1gb, it is quite bad idea to load full tree to memory.
In this case need to combine parsing from filesystem with inserting to db.

