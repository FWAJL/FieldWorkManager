<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FieldAnalyteController extends \Library\BaseController {

  public function executeAdd(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\AnalyteHelper::AddAnalyte($this, $this->InitResponseWS());

    \Applications\PMTool\Helpers\CommonHelper::SetActiveTab(
            $this->user(), \Applications\PMTool\Resources\Enums\AnalyteTabKeys::FieldTab, \Library\Enums\SessionKeys::TabActiveAnalyte);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::FieldAnalyte,
        "resx_key" => $this->action(),
        "step" => $result["dataId"] > 0 ? "success" : "error"
    ));
  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $analyte = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Field_analyte());
    $result["data"] = $analyte;

    $manager = $this->managers->getManagerOf($this->module());
    $result_edit = $manager->edit($analyte, "field_analyte_id");

    if ($result_edit) {
      $analyteMatch =
              \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById(
                      $analyte->field_analyte_id(), "field_analyte_id", $pm, \Library\Enums\SessionKeys::PmFieldAnalytes);

      $pm[\Library\Enums\SessionKeys::PmFieldAnalytes][$analyteMatch["key"]] = $analyte;
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pm);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::FieldAnalyte,
        "resx_key" => $this->action(),
        "step" => $result_edit ? "success" : "error"
    ));
  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $db_result = FALSE;
    $analyte_id = intval($this->dataPost["itemId"]);

    $analyte = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($analyte_id, "field_analyte_id", $pm, \Library\Enums\SessionKeys::PmFieldAnalytes);

    if ($analyte["object"] !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($analyte["object"], "field_analyte_id");
      if ($db_result) {
        unset($pm[\Library\Enums\SessionKeys::PmFieldAnalytes][$analyte["key"]]);
        \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pm);
      }
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::FieldAnalyte,
        "resx_key" => $this->action(),
        "step" => $db_result !== FALSE ? "success" : "error"
    ));
  }

  public function executeGetList(\Library\HttpRequest $rq = NULL, $sessionProject = NULL, $isAjaxCall = FALSE) {
    // Init result
    $result = \Applications\PMTool\Helpers\LocationHelper::GetLocationList($this, $sessionProject);
    if ($isAjaxCall) {
      $step_result = $result[\Library\Enums\SessionKeys::ProjectLocations] !== NULL ? "success" : "error";
      $this->SendResponseWS(
              $result, array(
          "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::FieldAnalyte,
          "resx_key" => $this->action(),
          "step" => $step_result
      ));
    }
  }

  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $field_analyte_id = intval($this->dataPost["field_analyte_id"]);
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    if (count($pm[\Library\Enums\SessionKeys::PmFieldAnalytes]) > 0) {
      $analyte_selected =
              \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById(
                      $field_analyte_id, "field_analyte_id", $pm, \Library\Enums\SessionKeys::PmFieldAnalytes);
    }

    $result["field_analyte"] = $analyte_selected["object"];
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::FieldAnalyte,
        "resx_key" => $this->action(),
        "step" => ($analyte_selected !== NULL) ? "success" : "error"
    ));
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\AnalyteHelper::UpdateProjectAnalytes($this);

    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);

    \Applications\PMTool\Helpers\CommonHelper::SetActiveTab(
            $this->user(), \Applications\PMTool\Resources\Enums\AnalyteTabKeys::FieldTab, \Library\Enums\SessionKeys::TabActiveAnalyte);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::FieldAnalyte,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["arrayOfValues"])) ? "success" : "error"
    ));
  }

}
