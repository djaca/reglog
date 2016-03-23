<?php
    require 'core/init.php';


    $database->query('DROP TABLE IF EXISTS reglog.users');
    $database->query('DROP TABLE IF EXISTS reglog.users_permissions');

    $database->query('CREATE TABLE IF NOT EXISTS users (
                                      id int(11) NOT NULL AUTO_INCREMENT,
                                      username varchar(20) NOT NULL,
                                      email varchar(255) NOT NULL,
                                      first_name varchar(50) DEFAULT NULL,
                                      last_name varchar(50) DEFAULT NULL,
                                      password varchar(255) NOT NULL,
                                      active tinyint(1) NOT NULL,
                                      active_hash varchar(255) DEFAULT NULL,
                                      recover_hash varchar(255) DEFAULT NULL,
                                      remember_identifier varchar(255) DEFAULT NULL,
                                      remember_token varchar(255) DEFAULT NULL,
                                      created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                      updated_at timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (id)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

    $database->query('CREATE TABLE IF NOT EXISTS users_permissions (
                                                  id int(11) NOT NULL AUTO_INCREMENT,
                                                  user_id int(11) NOT NULL,
                                                  is_admin tinyint(4) NOT NULL,
                                                  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                  updated_at timestamp NULL DEFAULT NULL,
                                                  PRIMARY KEY (id)
                                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8');
?>

    <a href="<?= getenv('BASE_URL') ?>">Home</a>