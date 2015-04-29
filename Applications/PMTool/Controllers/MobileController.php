<?php

/**
 *
 * @package     Basic MVC framework test
 * @author      FWM DEV Team
 * @copyright   Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * MobileController Class
 *
 * @package     Applications 
 * @subpackage  PMTool
 * @category    Controllers
 * @author      FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Controllers;
use Applications\PMTool\Helpers\UserHelper;
use Applications\PMTool\Models\Dao\User;
if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');
  
/********  Mobile Controller   ************/

class MobileController extends \Library\BaseController {

  /**
   * Method For Mobile Controller
   * 
   * @param \Library\HttpRequest $rq: the request
   */
 public function executeloadView(\Library\HttpRequest $rq) {
  
  $this->page->addVar( \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules,$this->app()->router()->selectedRoute()->phpModules());
 
  }

  
  
  
}
