<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GlobalKeys.php
 * 
 * It lists all the resource keys used globally in a given web app.
 *
 * @author jl
 */
namespace Library\Enums\ResourceKeys;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

abstract class GlobalAppKeys {
  /**
   * Page titles
   */
  const DefaultPageTitle = "DefaultPageTitle";
  const HomePageTitle = "HomePageTitle";
  const AboutPageTitle = "AboutPageTitle";
  const ResumePageTitle = "ResumePageTitle";
  const ContactPageTitle = "ContactPageTitle";
  /**
   * 
   */
  
}

?>
