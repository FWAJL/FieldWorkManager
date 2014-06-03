<?php 

namespace Library;

if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
abstract class ApplicationComponent
{
  protected $app;
  
  public function __construct(Application $app)
  {
    $this->app = $app;
  }
  
  public function app()
  {
    return $this->app;
  }
}