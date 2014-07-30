<?php

namespace Library\BO;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Project extends \Library\Entity{
  public 
    $project_id,
    $project_name,
    $project_number,
    $project_desc,
    $active,
    $visible,
    $pm_id;

  const 
    PROJECT_ID_ERR = 0,
    PROJECT_NAME_ERR = 1,
    PROJECT_NUMBER_ERR = 2,
    PROJECT_DESC_ERR = 3,
    ACTIVE_ERR = 4,
    VISIBLE_ERR = 5,
    PM_ID_ERR = 6;

  // SETTERS //
  public function setProject_id($project_id) {
    if (empty($project_id)) {
      $this->erreurs[] = self::PROJECT_ID_ERR;
    } else {
      $this->project_id = $project_id;
    }
  }

  public function setProject_name($project_name) {
    if (empty($project_name)) {
      $this->erreurs[] = self::PROJECT_NAME_ERR;
    } else {
      $this->project_name = $project_name;
    }
  }

  public function setProject_number($project_number) {
    if (empty($project_number)) {
      $this->erreurs[] = self::PROJECT_NUMBER_ERR;
    } else {
      $this->project_number = $project_number;
    }
  }

  public function setProject_desc($project_desc) {
    if (empty($project_desc)) {
      $this->erreurs[] = self::PROJECT_DESC_ERR;
    } else {
      $this->project_desc = $project_desc;
    }
  }

  public function setActive($active) {
    if (empty($active)) {
      $this->erreurs[] = self::ACTIVE_ERR;
    } else {
      $this->active = $active;
    }
  }

  public function setVisible($visible) {
    if (empty($visible)) {
      $this->erreurs[] = self::VISIBLE_ERR;
    } else {
      $this->visible = $visible;
    }
  }

  public function setPm_id($pm_id) {
    if (empty($pm_id)) {
      $this->erreurs[] = self::PM_ID_ERR;
    } else {
      $this->pm_id = $pm_id;
    }
  }

  // GETTERS //
  public function project_id() {
    return $this->project_id;
  }

  public function project_name() {
    return $this->project_name;
  }

  public function project_number() {
    return $this->project_number;
  }

  public function project_desc() {
    return $this->project_desc;
  }

  public function active() {
    return $this->active;
  }

  public function visible() {
    return $this->visible;
  }

  public function pm_id() {
    return $this->pm_id;
  }


}