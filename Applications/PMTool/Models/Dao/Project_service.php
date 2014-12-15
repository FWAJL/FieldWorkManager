<?php

namespace Applications\PMTool\Models\Dao;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Project_service extends \Library\Entity{
  public 
    $project_id,
    $service_id;

  const 
    PROJECT_ID_ERR = 0,
    SERVICE_ID_ERR = 1;

  // SETTERS //
  public function setProject_id($project_id) {
    $this->project_id = $project_id;
  }

  public function setService_id($service_id) {
      $this->service_id = $service_id;
  }

  // GETTERS //
  public function project_id() {
    return $this->project_id;
  }

  public function service_id() {
    return $this->service_id;
  }


}