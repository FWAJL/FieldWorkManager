<?php

namespace Library\DAL;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Managers {

    protected $api = null;
    protected $dao = null;
    protected $dal_folder_path = "";
    protected $managers = array();
    public $filters = null;

    public function __construct($api, $app) {
        $this->api = $api;
        $this->dao = PDOFactory::getMysqlConnexion($app);
        $this->dal_folder_path = $app->config()->get("DalFolderPath");
    }

    public function getManagerOf($module) {
        error_log("Module is <".$module.">");
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('The module provided is invalid.');
        }

        if (!isset($this->managers[$module])) {
            $manager = $this->dal_folder_path . $module . 'Dal';
            $this->filters = new DalFilters();
            $this->managers[$module] = new $manager($this->dao, $this->filters);
        }

        return $this->managers[$module];
    }

}
