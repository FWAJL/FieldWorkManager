<?php

/**
 *
 * @package		Basic MVC framework
 * @author		Jeremie Litzler
 * @copyright	Copyright (c) 2015
 * @license
 * @link
 * @since
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * ServiceHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	FormHelper
 * @author		FWM DEV Team
 * @link
 */

namespace Applications\PMTool\Helpers;

use Applications\PMTool\Models\Dao\Project_form;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FormHelper {

  public static function GetUserForms($caller, $sessionProject) {
    $result = array();
    if ($sessionProject !== NULL) {
      $manager = $caller->managers()->getManagerOf("UserForm");
      $userForm = new \Applications\PMTool\Models\Dao\User_form();
      $projectId = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();
      $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($caller->user());
      $userForm->setPm_id($pm[\Library\Enums\SessionKeys::PmObject]->pm_id());
      $result =
      $sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectUserForms] =
      $manager->selectMany($userForm, "pm_id");
      \Applications\PMTool\Helpers\ProjectHelper::SetCurrentSessionProject($caller->user(), $sessionProject);
    }
    return $result;
  }

  public static function GetMasterForms($caller, $sessionProject) {
    $result = array();
    if ($sessionProject !== NULL) {
      $manager = $caller->managers()->getManagerOf("MasterForm");
      $manager->setRootDirectory($caller->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
      $manager->setWebDirectory($caller->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $caller->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
      $masterForm = new \Applications\PMTool\Models\Dao\Master_form();
      $result =
      $sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectMasterForms] =
      $manager->selectMany($masterForm, "");
      \Applications\PMTool\Helpers\ProjectHelper::SetCurrentSessionProject($caller->user(), $sessionProject);
    }
    return $result;
  }

  public static function GetProjectForms($caller,$sessionProject) {
    $result = array();
    if ($sessionProject !== NULL) {
      $projectForm = new \Applications\PMTool\Models\Dao\Project_form();
      $projectId = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();
      $projectForm->setProject_id($projectId);
      $manager = $caller->managers()->getManagerOf("ProjectForm");
      $result = $sessionProject[\Library\Enums\SessionKeys::ProjectForms] = $manager->selectMany($projectForm, "project_id");
      \Applications\PMTool\Helpers\ProjectHelper::SetCurrentSessionProject($caller->user(), $sessionProject);
    }
    return $result;
  }

  public static function GetFormsFromProjectForms(\Library\User $user, $sessionProject) {
    $matches = array();
    $matches[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_forms] = array();
    $matches[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::master_forms] = array();
    
    foreach ($sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectMasterForms] as $master_form) {
      foreach ($sessionProject[\Library\Enums\SessionKeys::ProjectForms] as $form) {
        if (intval($form->master_form_id()) === intval($master_form->form_id())) {
          array_push($matches[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::master_forms], $master_form);
          break;
        }
      }
    }
    foreach ($sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectUserForms] as $user_form) {
      foreach ($sessionProject[\Library\Enums\SessionKeys::ProjectForms] as $form) {
        if (intval($form->user_form_id()) === intval($user_form->form_id())) {
          array_push($matches[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_forms], $user_form);
          break;
        }
      }
    }
    if(empty($matches[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::master_forms])) {
      unset($matches[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::master_forms]);
    }
    if(empty($matches[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_forms])) {
      unset($matches[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_forms]);
    }
    return $matches;
  }

  public static function SetPropertyNamesForDualList() {
    return array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id => "form_id",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name => "title",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active => "form_id",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_identifier => "data_identifier",
    );
  }

  public static function FilterFormsToExclude($forms, $filterForms, $form_id) {
    $filtered_forms = array();
    foreach ($forms as $form) {
      $to_add = TRUE;
      foreach ($filterForms as $filterForm) {
        if (intval($form->form_id()) === intval($filterForm->$form_id())) {
          $to_add = FALSE;
          break;
        }
      }
      if ($to_add) { array_push($filtered_forms, $form); }
    }
    return $filtered_forms;
  }

  public static function FilterFormsByGivenId($forms, $form_id, $form_id_value) {
    $filtered_forms = array();
    foreach($forms as $form) {
      $to_add = TRUE;
      if(intval($form->$form_id()) == intval($form_id_value)) {
        $to_add = FALSE;
      }
      if($to_add) { $filtered_forms[] = $form; }
    }
    return $filtered_forms;
  }

  public static function PrepareUserFormObject($dataPost) {
    $form = new \Applications\PMTool\Models\Dao\User_form();
    $form->setPm_id($dataPost['pm_id']);
    $form->setTitle($dataPost['title']);
    $form->setCategory(null);
    return $form;
  }

  public static function GetAUserForm(\Library\User $user, $form_id) {
    $match = NULL;
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($user);
    $forms = $sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectUserForms];
    if ($forms !== NULL) {
      $match = CommonHelper::FindObjectByIntValue($form_id, "form_id", $forms);
    }
    return $match;
  }

}

