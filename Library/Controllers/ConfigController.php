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
 * Config controller Class
 *
 * @package		Library
 * @subpackage	Controllers
 * @category	ConfigController
 * @author		FWM DEV Team
 * @link
 */

namespace Library\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ConfigController extends \Library\BaseController {

  public function executeGetSettingValue(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(
        array("directory" => "common", "resx_file" => "ws_defaults", "resx_key" => "", "step" => "error")
    );
    $result = \Library\Utility\ConfigHelper::GetValue($this, $rq, $result);

    if ($result["method"] === \Library\Enums\GenericAppKeys::GET_METHOD) {
      echo '<pre>', print_r($result), '</pre>';
    } else {
      $this->SendResponseWS(
          $result, array(
        "directory" => "common",
        "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::Config,
        "resx_key" => $this->action(),
        "step" =>
        $result[$rq->getData("key")] !== NULL || $result[$rq->getData("key")] !== "" > 0 ?
            "success" : "error"
          )
      );
    }
  }

}
