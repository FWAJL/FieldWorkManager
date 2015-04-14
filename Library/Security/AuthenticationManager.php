<?php

namespace Library\Security;


use Library\Interfaces\IUser;

class AuthenticationManager
{
  private $app;

  public function __construct($app)
  {
    $this->app = $app;
  }

  public function authenticate(IUser $user)
  {
    $this->app->user->setAuthenticated();
    //set role
    $this->app->user->setRole($user->getRole());
    //store user in session
    $this->app->user->setAttribute(\Library\Enums\SessionKeys::UserConnected, $user);
    $this->app->user->setUserType($user->getType());
    $this->app->user->setUserTypeId($user->getTypeValue());
  }

  public function deauthenticate()
  {
    $this->app->user->setAuthenticated(FALSE);
    $this->app->user->unsetAttribute(\Library\Enums\SessionKeys::UserConnected);
    session_destroy();
  }
} 