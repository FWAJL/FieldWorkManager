<?php

namespace Library\DAL;

class PDOFactory extends \Library\BL\Core\ApplicationComponent {

  public static function getMysqlConnexion($currentApp) {
    $host = $currentApp->config()->get("Myslq_host");
    $user = $currentApp->config()->get("Mysql_user");
    $pwd = $currentApp->config()->get("Mysql_pwd");
    $db_name = $currentApp->config()->get("Mysql_db_name");
    $db = new \PDO('mysql:host=' . $host . ';dbname=' . $db_name, $user, $pwd);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $db;
  }

}
