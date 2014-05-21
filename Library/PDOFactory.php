<?php

namespace Library;

class PDOFactory {

    public static function getMysqlConnexion() {
        $db = null;//new \PDO('mysql:host=localhost;dbname=news_project', 'root', '');
        //$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $db;
    }

}
