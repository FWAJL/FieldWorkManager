<?php

namespace Applications\PMTool\Controllers;

use Applications\PMTool\Helpers\UserHelper;
use Applications\PMTool\Models\Dao\User;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class UserController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {  }

  public function executeShowDetails(\Library\HttpRequest $rq) {
    //Load Modules for view
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_form, $modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_form]);
    if($this->app->user()->getUserType()=="pm_id") {

      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_details, $modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::pm_form]);
    }
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_details_buttons, $modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_details_buttons]);

  }

  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of pms stored in session
    $users = \Applications\PMTool\Helpers\UserHelper::GetAndStoreUsersInSession($this);
    $categorizedUsers = \Applications\PMTool\Helpers\UserHelper::CategorizeUsersList($users);
    $data = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left => $categorizedUsers,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\UserHelper::SetPropertyNamesForDualList()
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
    //Load Modules for view
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeEditCurrent(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $user = $this->app->user()->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $dataPost['user_login'] = $user->user_login();
    $changePassword = ($dataPost['user_password']!='')?true:false;
    $user->setUser_hint($dataPost['user_hint']);
    if($changePassword) {
      $protect = new \Library\BL\Core\Encryption();
      $user->setUser_password($protect->Encrypt($this->app->config->get("encryption_key"), $dataPost['user_password']));
    }
    $manager = $this->managers->getManagerOf('User');
    $result_insert = $manager->edit($user, "user_id");
    //Init PDO
    if($result_insert){
      $this->app->user()->setAttribute(\Library\Enums\SessionKeys::UserConnected,$user);
      if($this->app->user()->getUserType()=="pm_id") {
        $pmSession = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
        $this->dataPost["pm_id"] = ($pmSession === NULL) ? NULL : $pmSession[\Library\Enums\SessionKeys::PmObject]->pm_id();
        $pm = \Applications\PMTool\Helpers\UserHelper::PreparePmObject($this->dataPost());
        $result["data"] = $pm;

        $manager = $this->managers->getManagerOf($this->module);
        $result_insert = $manager->edit($pm, "pm_id");
        if($result_insert) {
          $pmSession[\Library\Enums\SessionKeys::PmObject] = $pm;
          \Applications\PMTool\Helpers\PmHelper::UpdateSessionPm($this->app->user(),$pmSession,true);
        }
      }
    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => $result_insert ? "success" : "error"
    ));
  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $userId = $dataPost['user_id'];
    $result_type = true;
    $result_user = false;
    //Init PDO
    if($this->app->user()->getRole()==1) {
      $users = \Applications\PMTool\Helpers\UserHelper::GetAndStoreUsersInSession($this);
      $user = \Applications\PMTool\Helpers\CommonHelper::FindObjectByIntValue(intval($userId),'user_id',$users);
      $user->setUser_login($dataPost['user_login']);
      $user->setUser_hint($dataPost['user_hint']);
      if($dataPost['user_password']!="") {
        $protect = new \Library\BL\Core\Encryption();
        $user->setUser_password($protect->Encrypt($this->app->config->get("encryption_key"), $dataPost['user_password']));
      }
      $manager = $this->managers->getManagerOf($this->module);
      $result['user'] = $result_user = $manager->edit($user, "user_id");
      if($result_user) {
        $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::AllUsers);
        if($user->user_type()=='pm_id'){
          $pm =  \Applications\PMTool\Helpers\UserHelper::PreparePmObject($dataPost);
          $manager = $this->managers->getManagerOf('Pm');
          $result['user_type'] = $result_type = $manager->edit($pm,'pm_id');
        }
      }
    }
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => ($result_user&&$result_type) ? "success" : "error"
    ));
  }

  public function executeEditTechnician(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $technicianId = $dataPost['technician_id'];
    $result_user = false;
    //Init PDO
    $manager = $this->managers->getManagerOf('User');
    $technician = $manager->selectUserByTypeId('technician_id',$technicianId);
    $technician->setUser_hint($dataPost['user_hint']);
    if($dataPost['user_password']!="") {
      $protect = new \Library\BL\Core\Encryption();
      $technician->setUser_password($protect->Encrypt($this->app->config->get("encryption_key"), $dataPost['user_password']));
    }
    $manager = $this->managers->getManagerOf($this->module);
    $result['user'] = $result_user = $manager->edit($technician, "user_id");
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => ($result_user) ? "success" : "error"
    ));
  }

  public function executeShowForm(\Library\HttpRequest $rq) {
    //set user types for dropdown menu selection
    $userTypes = array('pm_id');
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_types,$userTypes);
    //Load Modules for view
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeAdd(\Library\HttpRequest $rq) {
    // Init result sent to client (e.g. browser)
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    //.. and build the object to query the DB
    $user = \Applications\PMTool\Helpers\UserHelper::PrepareUserObject($dataPost,$this->app->config, true);
    $result["dataIn"]['user'] = $user;
    if(isset($dataPost['user_type']) && $dataPost['user_type']!="") {
      if($dataPost['user_type']=="pm_id") {
        $pm = \Applications\PMTool\Helpers\UserHelper::PreparePmObject($dataPost);
        $manager = $this->managers->getManagerOf('Pm');
        $pmId = $manager->add($pm);
        if((intval($pmId)) > 0) {
          $result['dataOut']['pm']=$this->_StoreUserInDb($user, $dataPost['user_type'], $pmId);
        }
      }
    } else {
      $user = $this->_StoreUserInDb($user, "", "");
    }
    if(($user instanceof \Applications\PMTool\Models\Dao\User) && intval($user->user_id())>0) {
      //add to list of users only if new user isn't admin
      if($user->user_role_id()!=1) {
        \Applications\PMTool\Helpers\UserHelper::AddNewUserToSession($this, $user);
      }
      $result['dataOut']['user'] = $user;
    }
    //Send the response to browser
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => (($user instanceof \Applications\PMTool\Models\Dao\User) && intval($user->user_id())>0) ? "success" : "error"
    ));
  }

  public function executeGetItem(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $userId = $this->dataPost["user_id"];

    $users = \Applications\PMTool\Helpers\UserHelper::GetAndStoreUsersInSession($this);
    $user = $result['user'] = \Applications\PMTool\Helpers\CommonHelper::FindObjectByIntValue(intval($userId),'user_id',$users);
    if($user instanceof \Applications\PMTool\Models\Dao\User){
      if($user->user_type()=="pm_id") {
        $pm = new \Applications\PMTool\Models\Dao\Project_manager();
        $pm->setPm_id($user->user_value());
        $manager = $this->managers->getManagerOf('Pm');
        $pms = $manager->selectMany($pm, 'pm_id');
        $pm = $pms[0];
        $result['pm'] = $pm;
      }
    } else {
      $result['user'] = array();
    }
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => count($result['user']) ? "success" : "error"
    ));
  }

  public function executeGetTechnicianItem(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $technicianId = $this->dataPost["technician_id"];

    $manager = $this->managers->getManagerOf('User');
    $technician = $manager->selectUserByTypeId('technician_id',$technicianId);

    if($technician instanceof \Applications\PMTool\Models\Dao\User){
      $result['user'] = $technician;
    } else {
      $result['user'] = array();
    }
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => count($result['user']) ? "success" : "error"
    ));
  }

  public function executeGetCurrent(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $user = $this->app->user()->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $result["pm"] = NULL;
    $result['user'] = $user;
    if($this->app->user()->getUserType()=="pm_id") {
      $pm_selected = $this->_GetPmFromSession($this->app->user()->getUserTypeId());
      $result["pm"] = $pm_selected;
      $result["user_type"] = $this->app->user()->getUserType();
    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => ( $result["user"] !== NULL) ? "success" : "error"
    ));
  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $db_result = FALSE;
    $db_result2 = TRUE;
    $userId = intval($this->dataPost["user_id"]);
    $isAdmin = $this->app()->user()->getRole();
    //Load interface to query the database
    if ($isAdmin == 1) {
      $users = \Applications\PMTool\Helpers\UserHelper::GetAndStoreUsersInSession($this);
      $user = \Applications\PMTool\Helpers\CommonHelper::FindObjectByIntValue(intval($userId),'user_id',$users);
      $manager = $this->managers->getManagerOf($this->module);
      $db_result = $manager->delete($user, "user_id");
      if($db_result !== FALSE) {
        if($user->user_type()=='pm_id') {
          $db_result2 = FALSE;
          $pm = new \Applications\PMTool\Models\Dao\Project_manager();
          $pm->setPm_id($user->user_value());
          $manager = $this->managers->getManagerOf('Pm');
          $db_result2 = $manager->delete($pm, 'pm_id');
        }
        $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::AllUsers);
      }

    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => ($db_result !== FALSE && $db_result2 !== FALSE) ? "success" : "error"
    ));
  }

  /**
   * Find a pm from an id
   *
   * @param int $pm_id : the id of the pm to find
   * @return \Applications\PMTool\Models\Dao\Pm $pmMatch : the match
   */
  private function _GetPmFromSession($pm_id) {
    $pms = array();
    $pmMatch = NULL;
    $pm = \Applications\PMTool\Helpers\PmHelper::GetSessionPm($this->app->user(),$pm_id);
    $pmMatch = $pm[\Library\Enums\SessionKeys::PmObject];
    return $pmMatch;
  }

  private function _StoreUserInDb($user, $userType, $userTypeId) {
    $role = \Applications\PMTool\Helpers\UserHelper::GetRoleFromType($userType);
    $user->setUser_role_id($role);
    if($userType!="") {
      $user->setUser_type($userType);
      $user->setUser_value($userTypeId);
    } else {
      $user->setUser_type("");
      $user->setUser_value("");
      $user->setUser_role_id(1);
    }
    $manager = $this->managers->getManagerOf($this->module);
    $res = $manager->add($user);
    if(intval($res)>0) {
      $user->setUser_id($res);
      return $user;
    } else {
      return $res;
    }
  }

}
