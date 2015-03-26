<?php

namespace Applications\PMTool;

use Library\Interfaces\IUser;
use Library\Security\IAuthProvider;

class AuthProvider implements IAuthProvider
{
  private $encryptionKey;
  private $loginManager;
  private $user = null;
  private $userType = null;

  public function __construct($encryptionKey, $loginManager)
  {
    $this->encryptionKey = $encryptionKey;
    $this->loginManager = $loginManager;
  }

  public function createUser($data)
  {
    $protect = new \Library\BL\Core\Encryption();

    $user = new \Applications\PMTool\Models\Dao\User();
    $user->setUser_login($data["username"]);
    if (!isset($data["encrypt_pwd"])) {
      $user->setUser_password($data["pwd"]);
    } else {
      $user->setUser_password($protect->Encrypt($this->encryptionKey, $data["pwd"]));
    }
    //Search for user in DB and return him
    $user_db =  $this->loginManager->selectOne($user);
    if (count($user_db) === 1) {
      if (!isset($data["encrypt_pwd"])) {
        $user->setUser_password($protect->Encrypt($this->encryptionKey, $user->password()));
        $user->user_id = $user_db[0]->user_id;
        $this->loginManager->update($user);
      }
      $this->user = $user_db[0];
    }
    //Search for user in DB and return him
    $user_type = $this->loginManager->selectUserType($this->user);
    if (count($user_type) === 1) {
      $this->userType = $user_type[0];
    }
  }

  /**
   * @return IUser
   */
  public function getUser()
  {
    return $this->user;
  }

  public function getUserType()
  {
    return $this->userType;
  }
}