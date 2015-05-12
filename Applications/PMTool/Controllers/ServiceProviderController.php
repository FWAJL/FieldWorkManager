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
 * Template controller Class
 *
 * @package		Application/PMTool
 * @subpackage	Controllers
 * @category	TemplateController
 * @author		FWM DEV Team
 * @link
 */
namespace Applications\PMTool\Controllers;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class ServiceProviderController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {
  }

  public function executeServiceDiscussion(\Library\HttpRequest $rq) {
    $discussionId = $rq->getData("discussion_id");
    $discussionArray = \Applications\PMTool\Helpers\DiscussionHelper::GetDiscussionByIdFromDB($this, $discussionId);
    \Applications\PMTool\Helpers\DiscussionHelper::SetCurrentDiscussion($this->user, $discussionArray[\Library\Enums\SessionKeys::DiscussionObj], $discussionArray[\Library\Enums\SessionKeys::DiscussionPeople]);
    $currentDiscussion = $discussionArray;
    if($currentDiscussion){
      $manager = $this->managers()->getManagerOf('User');
      $discussion_person = \Applications\PMTool\Helpers\CommonHelper::FindObjectByIntValue(1,'discussion_person_is_author',$currentDiscussion[\Library\Enums\SessionKeys::DiscussionPeople]);
      $discussion_user_type = $manager->selectUserTypeObjectByUserId($discussion_person->user_id());
      if($discussion_user_type) {
        $currentDiscussion['comm_with'] = $discussion_user_type;
        $currentDiscussion['comm_type'] = \Applications\PMTool\Helpers\UserHelper::FindUserTypeFromObject($discussion_user_type);
      }
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentDiscussion, $currentDiscussion);
    }

    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);


  }

}