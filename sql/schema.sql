DROP TABLE IF EXISTS node;
CREATE TABLE node (
    id int(11) NOT NULL auto_increment,
    path longtext,
    depth int(11) NOT NULL default '0',
    PRIMARY KEY (id),
    KEY node_depth(depth),
    KEY node_path(path(1000))
) ENGINE=InnoDB;