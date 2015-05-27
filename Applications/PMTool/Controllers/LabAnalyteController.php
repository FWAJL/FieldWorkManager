<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class LabAnalyteController extends \Library\BaseController {
    
  public function executeShowForm(\Library\HttpRequest $rq) {
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }
  
  public function executeUploadList(\Library\HttpRequest $rq) {
    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"analyte", "targetaction": "uploadList", "targetattr": ["activequestion-fieldanalyte-uploadList-headerH4", "inactivequestion-fieldanalyte-uploadList-headerH4", "activequestion-labanalyte-uploadList-headerH4", "inactivequestion-labanalyte-uploadList-headerH4"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    if ($tabsStatus === NULL) {
      \Applications\PMTool\Helpers\AnalyteHelper::AddTabsStatus($this->user());
      $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    }
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, $tabsStatus);
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());

    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this, TRUE);

    $data_common_field_analyte = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "common_field_analyte",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetValueInSession(
                $this->user(), \Library\Enums\SessionKeys::CommonFieldAnalytes),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("common_field_analyte")
    );
    $this->page()->addVar(
            "data_common_field_analyte", $data_common_field_analyte);

    $data_common_lab_analyte = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "common_lab_analyte",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetValueInSession(
                $this->user(), \Library\Enums\SessionKeys::CommonLabAnalytes),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("common_lab_analyte")
    );
    $this->page()->addVar(
            "data_common_lab_analyte", $data_common_lab_analyte);
  }
  
   public function executeListAll(\Library\HttpRequest $rq) {
    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    if ($tabsStatus === NULL) {
      \Applications\PMTool\Helpers\AnalyteHelper::AddTabsStatus($this->user());
      $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    }
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, $tabsStatus);

    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject)
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::AnalyteListAll);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"analyte", "targetaction": "list", "targetattr": ["active-fieldanalyte-header","inactive-fieldanalyte-header","active-labanalyte-header","inactive-labanalyte-header"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    //Get confirm msg for analyte deletion from showForm screen
    $confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"analyte", "targetaction": "list", "operation": ["deleteField", "deleteLab", "noAnalyteForProject","noAnalyteAvailable"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);

    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    if (!isset($pm[\Library\Enums\SessionKeys::PmFieldAnalytes]) &&
            !isset($pm[\Library\Enums\SessionKeys::PmLabAnalytes]) &&
            count($pm[\Library\Enums\SessionKeys::PmFieldAnalytes]) > 0 &&
            count($pm[\Library\Enums\SessionKeys::PmLabAnalytes]) > 0) {
      \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);
    }
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());

    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"analyte", "targetaction": "listAll", "operation": ["edit"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    //variables for the field analyte module
    $field_object_properties = \Applications\PMTool\Helpers\CommonHelper::SetDynamicPropertyNamesForDualList(
                    "field_analyte", \Applications\PMTool\Helpers\AnalyteHelper::GetListPropertiesForFieldAnalyte());
    $field_analytes = $pm[\Library\Enums\SessionKeys::PmFieldAnalytes];
    $project_field_analytes = \Applications\PMTool\Helpers\AnalyteHelper::FilterAnalytesByProjectAnalytesList($this);
    $field_analytes = \Applications\PMTool\Helpers\CommonHelper::FilterObjectsToExcludeRelatedObject($field_analytes, $project_field_analytes, "field_analyte_id");

    $data_field_analyte = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "fieldanalyte",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $field_analytes,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $project_field_analytes,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => $field_object_properties,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => $field_object_properties
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::data_field_analyte, $data_field_analyte);

    //variable for the lab analyte module
    $lab_object_properties = \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("lab_analyte");
    $lab_analytes = $pm[\Library\Enums\SessionKeys::PmLabAnalytes];
    $project_lab_analytes = \Applications\PMTool\Helpers\AnalyteHelper::FilterAnalytesByProjectAnalytesList($this, FALSE);
    $lab_analytes = \Applications\PMTool\Helpers\CommonHelper::FilterObjectsToExcludeRelatedObject($lab_analytes, $project_lab_analytes, "lab_analyte_id");

    $data_lab_analyte = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "labanalyte",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $lab_analytes,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $project_lab_analytes,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => $lab_object_properties,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => $lab_object_properties
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::data_lab_analyte, $data_lab_analyte);


    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }
  
  public function executeUploadCommonAnalytes(\Library\HttpRequest $rq) {
    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    if ($tabsStatus === NULL) {
      \Applications\PMTool\Helpers\AnalyteHelper::AddTabsStatus($this->user());
      $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    }
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, $tabsStatus);
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());

    \Applications\PMTool\Helpers\AnalyteHelper::StoreCommonListData($this);
    
    $data_common_lab_analyte = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "common_lab_analyte",
         \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetValueInSession(
                $this->user(), \Library\Enums\SessionKeys::CommonLabAnalytes),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("common_lab_analyte")
    );
    $this->page()->addVar(
            "data_common_lab_analyte", $data_common_lab_analyte);
  }

  public function executeAdd(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\AnalyteHelper::AddAnalyte($this, $this->InitResponseWS(), FALSE, FALSE);

    \Applications\PMTool\Helpers\CommonHelper::SetActiveTab(
            $this->user(), \Applications\PMTool\Resources\Enums\AnalyteTabKeys::LabTab, \Library\Enums\SessionKeys::TabActiveAnalyte);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::LabAnalyte,
        "resx_key" => $this->action(),
        "step" => $result["dataId"] > 0 ? "success" : "error"
    ));
  }

  public function executeAddCommon(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\AnalyteHelper::AddAnalyte($this, $this->InitResponseWS(), FALSE, TRUE);

    \Applications\PMTool\Helpers\CommonHelper::SetActiveTab(
            $this->user(), \Applications\PMTool\Resources\Enums\AnalyteTabKeys::FieldTab, \Library\Enums\SessionKeys::TabActiveAnalyte);

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

    \Applications\PMTool\Helpers\CommonHelper::SetActiveTab(
            $this->user(), \Applications\PMTool\Resources\Enums\AnalyteTabKeys::LabTab, \Library\Enums\SessionKeys::TabActiveAnalyte);

    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $analyte = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Lab_analyte());
    $result["data"] = $analyte;

    $manager = $this->managers->getManagerOf($this->module());
    $result_edit = $manager->edit($analyte, "lab_analyte_id");

    if ($result_edit) {
      $analyteMatch = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById(
                      $analyte->lab_analyte_id(), "lab_analyte_id", $pm, \Library\Enums\SessionKeys::PmLabAnalytes);

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

    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $db_result = FALSE;
    $analyte_id = intval($this->dataPost["itemId"]);

    $analyte = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($analyte_id, "lab_analyte_id", $pm, \Library\Enums\SessionKeys::PmLabAnalytes);

    if ($analyte["object"] !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($analyte["object"], "lab_analyte_id");
      if ($db_result) {
        unset($pm[\Library\Enums\SessionKeys::PmLabAnalytes][$analyte["key"]]);
        \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pm);
      }
    }
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
      $analyte_selected = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById(
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

  /**
  * Ajax response for deleteting common lab analytes
  */
  public function executeDeleteCommon(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    $analyte_deleted = 0;
    $analyte = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($this->dataPost['analyte_id'], 
                    "common_lab_analyte_id", $_SESSION, \Library\Enums\SessionKeys::CommonLabAnalytes);

    if ($analyte["object"] !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($analyte["object"], "common_lab_analyte_id");
      if ($db_result) {
        unset($_SESSION[\Library\Enums\SessionKeys::CommonLabAnalytes][$analyte["key"]]);
        $analyte_deleted = 1;    
      }
    }

    $this->SendResponseWS(
            $result, array(
            "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::LabAnalyte,
            "resx_key" => $this->action(),
            "step" => ($analyte_deleted === 1) ? "success" : "error"
        )
    );
  }

}
