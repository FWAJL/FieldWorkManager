<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_coc_info extends \Library\Entity{  public     $task_id,    $resource_id,    $po_number,    $lab_instructions,    $lab_sample_type,    $lab_sample_tat,    $project_id_num,    $results_to_name,    $results_to_company,    $results_to_address,    $results_to_phone,    $results_to_email;
  const     TASK_ID_ERR = 0,    RESOURCE_ID_ERR = 1,    PO_NUMBER_ERR = 2,    LAB_INSTRUCTIONS_ERR = 3,    LAB_SAMPLE_TYPE_ERR = 4,    LAB_SAMPLE_TAT_ERR = 5,    PROJECT_ID_NUM_ERR = 6,    RESULTS_TO_NAME_ERR = 7,    RESULTS_TO_COMPANY_ERR = 8,    RESULTS_TO_ADDRESS_ERR = 9,    RESULTS_TO_PHONE_ERR = 10,    RESULTS_TO_EMAIL_ERR = 11;
  // SETTERS //  public function setTask_id($task_id) {    if (empty($task_id)) {      $this->erreurs[] = self::TASK_ID_ERR;    } else {      $this->task_id = $task_id;    }  }
  public function setResource_id($resource_id) {    if (empty($resource_id)) {      $this->erreurs[] = self::RESOURCE_ID_ERR;    } else {      $this->resource_id = $resource_id;    }  }
  public function setPo_number($po_number) {    if (empty($po_number)) {      $this->erreurs[] = self::PO_NUMBER_ERR;    } else {      $this->po_number = $po_number;    }  }
  public function setLab_instructions($lab_instructions) {    if (empty($lab_instructions)) {      $this->erreurs[] = self::LAB_INSTRUCTIONS_ERR;    } else {      $this->lab_instructions = $lab_instructions;    }  }
  public function setLab_sample_type($lab_sample_type) {    if (empty($lab_sample_type)) {      $this->erreurs[] = self::LAB_SAMPLE_TYPE_ERR;    } else {      $this->lab_sample_type = $lab_sample_type;    }  }
  public function setLab_sample_tat($lab_sample_tat) {    if (empty($lab_sample_tat)) {      $this->erreurs[] = self::LAB_SAMPLE_TAT_ERR;    } else {      $this->lab_sample_tat = $lab_sample_tat;    }  }
  public function setProject_id_num($project_id_num) {    if (empty($project_id_num)) {      $this->erreurs[] = self::PROJECT_ID_NUM_ERR;    } else {      $this->project_id_num = $project_id_num;    }  }
  public function setResults_to_name($results_to_name) {    if (empty($results_to_name)) {      $this->erreurs[] = self::RESULTS_TO_NAME_ERR;    } else {      $this->results_to_name = $results_to_name;    }  }
  public function setResults_to_company($results_to_company) {    if (empty($results_to_company)) {      $this->erreurs[] = self::RESULTS_TO_COMPANY_ERR;    } else {      $this->results_to_company = $results_to_company;    }  }
  public function setResults_to_address($results_to_address) {    if (empty($results_to_address)) {      $this->erreurs[] = self::RESULTS_TO_ADDRESS_ERR;    } else {      $this->results_to_address = $results_to_address;    }  }
  public function setResults_to_phone($results_to_phone) {    if (empty($results_to_phone)) {      $this->erreurs[] = self::RESULTS_TO_PHONE_ERR;    } else {      $this->results_to_phone = $results_to_phone;    }  }
  public function setResults_to_email($results_to_email) {    if (empty($results_to_email)) {      $this->erreurs[] = self::RESULTS_TO_EMAIL_ERR;    } else {      $this->results_to_email = $results_to_email;    }  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function resource_id() {    return $this->resource_id;  }
  public function po_number() {    return $this->po_number;  }
  public function lab_instructions() {    return $this->lab_instructions;  }
  public function lab_sample_type() {    return $this->lab_sample_type;  }
  public function lab_sample_tat() {    return $this->lab_sample_tat;  }
  public function project_id_num() {    return $this->project_id_num;  }
  public function results_to_name() {    return $this->results_to_name;  }
  public function results_to_company() {    return $this->results_to_company;  }
  public function results_to_address() {    return $this->results_to_address;  }
  public function results_to_phone() {    return $this->results_to_phone;  }
  public function results_to_email() {    return $this->results_to_email;  }
}