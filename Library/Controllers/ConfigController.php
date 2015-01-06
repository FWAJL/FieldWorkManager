<?php

/**
 *
 * @package		Basic MVC framework
 * @author		Jeremie Litzler
 * @copyright	Copyright (c) 2014
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
    $GET_METHOD = "get";
    $POST_METHOD = "post";
    $result = $this->InitResponseWS(
        array(
          "directory" => "common",
          "resx_file" => "ws_defaults",
          "resx_key" => "",
          "step" => "error")
    );

    if (!$this->dataPost()) {
      $result[$this->dataPost["key"]] = $this->app()->config()->get($this->dataPost["key"]);
      $result["method"] = $POST_METHOD;
    }
    if ($rq->getExists("key")) {
      $result[$rq->getData("key")] = $this->app()->config()->get($rq->getData("key"));
      $result["method"] = $GET_METHOD;
    }

    if ($result["method"] === $GET_METHOD) {
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
