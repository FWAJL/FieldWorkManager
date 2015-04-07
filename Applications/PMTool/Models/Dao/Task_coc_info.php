<?php

/**
 *
 * @package    Basic MVC framework
 * @author     Jeremie Litzler
 * @copyright  Copyright (c) 2015
 * @license
 * @link
 * @since
 * @filesource
 */
// ------------------------------------------------------------------------
/**
 *
 * Task_coc_info Dao Class
 *
 * @package     Application/PMTool
 * @subpackage  Models/Dao
 * @category    Task_coc_info
 * @author      FWM DEV Team
 * @link
 */

namespace Applications\PMTool\Models\Dao;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class Task_coc_info extends \Library\Entity {

  public
          $task_coc_id,
          $task_id,
          $service_id,
          $po_number,
          $lab_instructions,
          $lab_sample_type,
          $lab_sample_tat,
          $project_number,
          $results_to_name,
          $results_to_company,
          $results_to_address,
          $results_to_phone,
          $results_to_email;
          //$service_object;

  const
          TASK_COC_ID_ERR = 0,
          TASK_ID_ERR = 1,
          SERVICE_ID_ERR = 2,
          PO_NUMBER_ERR = 3,
          LAB_INSTRUCTIONS_ERR = 4,
          LAB_SAMPLE_TYPE_ERR = 5,
          LAB_SAMPLE_TAT_ERR = 6,
          PROJECT_NUMBER_ERR = 7,
          RESULTS_TO_NAME_ERR = 8,
          RESULTS_TO_COMPANY_ERR = 9,
          RESULTS_TO_ADDRESS_ERR = 10,
          RESULTS_TO_PHONE_ERR = 11,
          RESULTS_TO_EMAIL_ERR = 12;

  // SETTERS //
  public function setTask_coc_id($task_coc_id) {
    $this->task_coc_id = $task_coc_id;
  }

  public function setTask_id($task_id) {
    $this->task_id = $task_id;
  }

  public function setService_id($service_id) {
    $this->service_id = $service_id;
  }

  public function setPo_number($po_number) {
    $this->po_number = $po_number;
  }

  public function setLab_instructions($lab_instructions) {
    $this->lab_instructions = $lab_instructions;
  }

  public function setLab_sample_type($lab_sample_type) {
    $this->lab_sample_type = $lab_sample_type;
  }

  public function setLab_sample_tat($lab_sample_tat) {
    $this->lab_sample_tat = $lab_sample_tat;
  }

  public function setProject_number($project_number) {
    $this->project_number = $project_number;
  }

  public function setResults_to_name($results_to_name) {
    $this->results_to_name = $results_to_name;
  }

  public function setResults_to_company($results_to_company) {
    $this->results_to_company = $results_to_company;
  }

  public function setResults_to_address($results_to_address) {
    $this->results_to_address = $results_to_address;
  }

  public function setResults_to_phone($results_to_phone) {
    $this->results_to_phone = $results_to_phone;
  }

  public function setResults_to_email($results_to_email) {
    $this->results_to_email = $results_to_email;
  }
//  public function setService_object($object) {
//    $this->service_object = $object;
//  }
  

  // GETTERS //
  public function task_coc_id() {
    return $this->task_coc_id;
  }

  public function task_id() {
    return $this->task_id;
  }

  public function service_id() {
    return $this->service_id;
  }

  public function po_number() {
    return $this->po_number;
  }

  public function lab_instructions() {
    return $this->lab_instructions;
  }

  public function lab_sample_type() {
    return $this->lab_sample_type;
  }

  public function lab_sample_tat() {
    return $this->lab_sample_tat;
  }

  public function project_number() {
    return $this->project_number;
  }

  public function results_to_name() {
    return $this->results_to_name;
  }

  public function results_to_company() {
    return $this->results_to_company;
  }

  public function results_to_address() {
    return $this->results_to_address;
  }

  public function results_to_phone() {
    return $this->results_to_phone;
  }

  public function results_to_email() {
    return $this->results_to_email;
  }

}