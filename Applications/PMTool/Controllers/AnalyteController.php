<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class AnalyteController extends \Library\BaseController {

  public function executeShowForm(\Library\HttpRequest $rq) {
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());

    //variables for the field analyte module
    $field_object_properties = \Applications\PMTool\Helpers\CommonHelper::SetDynamicPropertyNamesForDualList(
            "field_analyte", 
            \Applications\PMTool\Helpers\AnalyteHelper::GetListPropertiesForFieldAnalyte());
    $field_analytes = $pm[\Library\Enums\SessionKeys::PmFieldAnalytes];
    $project_field_analytes = \Applications\PMTool\Helpers\AnalyteHelper::FilterAnalytesByProjectAnalytesList($this);
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

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    
  }

}
