<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class LabAnalyteController extends \Library\BaseController {

  public function executeAdd(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\AnalyteHelper::AddAnalyte($this, $this->InitResponseWS(), FALSE);

    \Applications\PMTool\Helpers\CommonHelper::SetActiveTab(
            $this->user(), \Applications\PMTool\Resources\Enums\AnalyteTabKeys::LabTab, \Library\Enums\SessionKeys::TabActiveAnalyte);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::LabAnalyte,
        "resx_key" => $this->action(),
        "step" => $result["dataId"] > 0 ? "success" : "error"
    ));
  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $analyte = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Lab_analyte());
    $result["data"] = $analyte;

    $manager = $this->managers->getManagerOf($this->module());
    $result_edit = $manager->edit($analyte, "lab_analyte_id");

    if ($result_edit) {
      $analyteMatch =
              \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById(
                      $analyte->field_analyte_id(), "lab_analyte_id", $pm, \Library\Enums\SessionKeys::PmLabAnalytes);

      $pm[\Library\Enums\SessionKeys::PmLabAnalytes][$analyteMatch["key"]] = $analyte;
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pm);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::LabAnalyte,
        "resx_key" => $this->action(),
        "step" => $result_edit ? "success" : "error"
    ));
  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::LabAnalyte,
        "resx_key" => $this->action(),
        "step" => $db_result !== FALSE ? "success" : "error"
    ));
  }

  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $lab_analyte_id = intval($this->dataPost["lab_analyte_id"]);
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    if (count($pm[\Library\Enums\SessionKeys::PmLabAnalytes]) > 0) {
      $analyte_selected =
              \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById(
                      $lab_analyte_id, "lab_analyte_id", $pm, \Library\Enums\SessionKeys::PmLabAnalytes);
    }

    $result["field_analyte"] = $analyte_selected["object"];
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::LabAnalyte,
        "resx_key" => $this->action(),
        "step" => ($analyte_selected !== NULL) ? "success" : "error"
    ));
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\AnalyteHelper::UpdateProjectAnalytes($this);

    \Applications\PMTool\Helpers\CommonHelper::SetActiveTab(
            $this->user(), \Applications\PMTool\Resources\Enums\AnalyteTabKeys::LabTab, \Library\Enums\SessionKeys::TabActiveAnalyte);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::LabAnalyte,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["arrayOfValues"])) ? "success" : "error"
    ));
  }

}
