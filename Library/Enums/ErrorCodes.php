<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ErrorCodes
 *
 * @author jl
 */
namespace Library\Enums;

abstract class ErrorCodes {

  /*
   * Standards HTTP error codes
   */
  const PageNotFound = 404;
  const ServerError = 500;
  /*
   * Application specific error codes
   */
  const ControllerNotExist = 1001;

}

?>
