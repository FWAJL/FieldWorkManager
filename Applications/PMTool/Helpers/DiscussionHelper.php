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
* CommonHelper Class
*
* @package		Application/PMTool
* @subpackage	Helpers
* @category	DiscussionHelper
* @author		FWM DEV Team
* @link
*/

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
exit('No direct script access allowed');

class DiscussionHelper {


  public static function GetDiscussionByIdFromDB($caller, $discussion_id) {
    $manager = $caller->managers()->getManagerOf('Discussion');
    $discussion = new \Applications\PMTool\Models\Dao\Discussion();
    $discussion->setDiscussion_id($discussion_id);
    $discussions = $manager->selectMany($discussion,'discussion_id');
    $discussion = $discussions[0];
    $manager = $caller->managers()->getManagerOf('DiscussionPerson');
    $discussion_person = new \Applications\PMTool\Models\Dao\Discussion_person();
    $discussion_person->setDiscussion_id($discussion->discussion_id());
    //select all connected people so we can store them in session
    $discussion_people = $manager->selectMany($discussion_person, 'discussion_id');
    $discussionArray = array(
      \Library\Enums\SessionKeys::DiscussionObj => $discussion,
      \Library\Enums\SessionKeys::DiscussionPeople => $discussion_people,
    );
    return $discussionArray;

  }

  public static function SetCurrentDiscussion($user, $discussion, $discussionPeople) {
    $user->unsetAttribute(\Library\Enums\SessionKeys::DiscussionThread);
    $currentDiscussionArray = array(
      \Library\Enums\SessionKeys::DiscussionObj => $discussion,
      \Library\Enums\SessionKeys::DiscussionPeople => $discussionPeople,
    );
    $user->setAttribute(\Library\Enums\SessionKeys::CurrentDiscussion,$currentDiscussionArray);

    return true;
  }

  public static function UnsetCurrentDiscussion($user) {
    $user->unsetAttribute(\Library\Enums\SessionKeys::CurrentDiscussion);
    return true;
  }

  public static function GetCurrentDiscussion($user) {
    $currentDiscussion = $user->getAttribute(\Library\Enums\SessionKeys::CurrentDiscussion);
    if(isset($currentDiscussion)) {
      return $currentDiscussion;
    } else {
      return false;
    }
  }

  public static function GetAllTaskDiscussions($caller,$task_id) {
    $manager = $caller->managers()->getManagerOf('Discussion');
    $discussion = new \Applications\PMTool\Models\Dao\Discussion();
    $discussion->setTask_id($task_id);
    $discussions = $manager->selectMany($discussion,'task_id',false);
    $caller->user()->setAttribute(\Library\Enums\SessionKeys::TaskDiscussions,$discussion);
    return $discussions;
  }

  public static function CheckIfDiscussionExistsByUsers($caller, $current_user, $recipient, $task_id) {
    $manager = $caller->managers()->getManagerOf('Discussion');
    $discussion = $manager->selectByUserPair($current_user->user_id(),$recipient->user_id(), $task_id);
    if($discussion) {
      return $discussion;
    } else {
      return false;
    }
  }

  public static function CreateNewDiscussion($caller, $discussionUsers, $task_id) {
    $manager = $caller->managers()->getManagerOf('Discussion');
    $discussion = new \Applications\PMTool\Models\Dao\Discussion();
    $discussion->setTask_id($task_id);
    $discussion_id = $manager->add($discussion);
    $current_user = $caller->user()->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    if($discussion_id>0){
      $manager = $caller->managers()->getManagerOf('DiscussionPerson');
      foreach($discussionUsers as $user) {
        $discussion_person = new \Applications\PMTool\Models\Dao\Discussion_person();
        $discussion_person->setUser_id($user->user_id());
        $discussion_person->setDiscussion_id($discussion_id);
        //check if this user is the current user so that we set it to author
        if($current_user->user_id() == $user->user_id()){
          $discussion_person->setDiscussion_person_is_author(1);
        } else {
          $discussion_person->setDiscussion_person_is_author(0);
        }
        $discussion_person_id = $manager->add($discussion_person);
        if($discussion_person_id<=0) {
          \Library\Core\Utility\Logger::LogEx(__CLASS__, __METHOD__, "::", "Discussion person insertion failed.");
        }

      }
      $manager = $caller->managers()->getManagerOf('Discussion');
      $discussion->setDiscussion_id($discussion_id);
      //fetch discussion with the time from db so we can set it as the current in the controller
      $discussion =$manager->selectMany($discussion,'discussion_id');
      return $discussion[0];
    } else {
      return false;
    }

  }

  public static function GetDiscussionThread($caller, $discussion) {
    $manager = $caller->managers()->getManagerOf('DiscussionContent');
    $personArray = array();
    foreach($discussion[\Library\Enums\SessionKeys::DiscussionPeople] as $person) {
      $personArray[] = $person->discussion_person_id();
    }
    $lastMessage = self::GetLastMessageFromThread($caller);
    if(!is_null($lastMessage)) {
      $lastMessageTime = $lastMessage->discussion_content_time();
    } else {
      $lastMessageTime = false;
    }
    $thread = $manager->selectDiscussionThread($personArray, $lastMessageTime);
    if(!is_null($lastMessage)) {
      if($thread) {
        $currentSessionThread = $caller->user()->getAttribute(\Library\Enums\SessionKeys::DiscussionThread);
        $caller->user()->setAttribute(\Library\Enums\SessionKeys::DiscussionThread,array_merge($thread,$currentSessionThread));
        return $caller->user()->getAttribute(\Library\Enums\SessionKeys::DiscussionThread);
      } else {
        return $caller->user()->getAttribute(\Library\Enums\SessionKeys::DiscussionThread);
      }
    } else {
      if($thread) {
        $caller->user()->setAttribute(\Library\Enums\SessionKeys::DiscussionThread,$thread);
        return $caller->user()->getAttribute(\Library\Enums\SessionKeys::DiscussionThread);
      } else {
        return false;
      }
    }
  }

  public static function GetLastMessageFromThread($caller) {
    $sessionThread = $caller->user()->getAttribute(\Library\Enums\SessionKeys::DiscussionThread);
    if(isset($sessionThread)) {
      $lastMessage = reset($sessionThread);
      return $lastMessage;
    } else {
      return null;
    }
  }

  public static function AddMessageToThread($caller, $userConnected, $currentDiscussion, $dataPost) {
    $manager = $caller->managers()->getManagerOf('DiscussionContent');
    $discussion_person = \Applications\PMTool\Helpers\CommonHelper::FindObjectByIntValue(intval($userConnected->user_id()), 'user_id', $currentDiscussion[\Library\Enums\SessionKeys::DiscussionPeople]);
    $discussion_content = new \Applications\PMTool\Models\Dao\Discussion_content();
    $discussion_content->setDiscussion_person_id($discussion_person->discussion_person_id());
    $discussion_content->setDiscussion_content_message($dataPost['discussion_content_message']);
    $discussion_content_id = $manager->add($discussion_content);

    self::GetDiscussionThread($caller, $currentDiscussion);

    return $discussion_content_id;
  }

  public static function SliceThread($thread, $time) {
    foreach($thread as $k=>$message) {
      if(strtotime($message->discussion_content_time()) <= strtotime($time)) {
        unset($thread[$k]);
      }
    }
    return $thread;
  }

  public static function GetOtherDiscussionPerson($user, $discussionPeople) {
    $userConnected = $user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    foreach($discussionPeople as $discussionPerson) {
      if(intval($discussionPerson->user_id()) !== intval($userConnected->user_id())){
        return $discussionPerson;
      }
    }
  }

}