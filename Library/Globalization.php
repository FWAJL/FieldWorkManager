<?php

/* * 
 * What type of resource will we have?
 *  - global resources that are used on every page, e.g. Application name.
 *  - common resources that are used on several pages, e.g. a link text like "Download"
 *  - local resources that are used in a specific page, e.g. title of paragraph, label, text paragraph url of a link
 *
 * Where do we get the resources from?
 * ==> type: common
 *  - one file per language
 *  - location: Applications/YourApp/Resources/Common
 * ==> type: local
 *  - one file per page and per language listing the resources using the valid keys
 *  - location: Applications/YourApp/Resources/Pages
 * 
 * Can the resources contains HTML??
 *  - yes but javascript must be escaped.
 * 
 * How do we load it?
 *  - we need to load everything when the app is started (e.g. in the Application class constructor)
 *  - loop through all the files in location above and store them in associative arrays (1 per type) so that we can find the value using
 *    + the type (local, common)
 *    + the language 
 *    + the page
 *    + the resource key
 *    + the value
 */

namespace Library;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class Globalization extends ApplicationComponent {

  protected $resoures_path = "";
  protected $res_common = array();
  protected $res_local = array();
  private $_files_common = null;
  private $_files_local = null;

  /*
   * Load the resources into each type array
   */

  public function loadResources() {
    $this->resoures_path = __ROOT__ . Enums\FolderName::AppsFolderName . $this->app->name;
    //Get list of resource files
    $this->_files_common = $this->getFilesList($this->resoures_path . Enums\FolderName::ResourceCommonFolderName);
    $this->_files_local = $this->getFilesList($this->resoures_path . Enums\FolderName::ResourceLocalFolderName);
    //For each resource type, load data into the appropriate array
    foreach ($this->_files_common as $file) {
      $this->loadFile("common", $this->resoures_path . Enums\FolderName::ResourceCommonFolderName . $file);
    }
    foreach ($this->_files_local as $file) {
      $this->loadFile("local", $this->resoures_path . Enums\FolderName::ResourceLocalFolderName . $file);
    }
  }

  private function getFilesList($dir) {
    return array_diff(scandir($dir), array('..', '.'));
  }

  private function loadFile($type, $file) {
    //Load xml
    $xml = new \DOMDocument;
    $xml->load($file);

    //Split file name to use it to store the resources in the array in a organized manner
    $params = $this->prepareParams($file);
    $params["type"] = $type;
    $this->storeContentsIntoArray($xml->getElementsByTagName('resource'), $params);
  }

  private function prepareParams($path) {
    $path_to_substr = explode("/", $path);
    $file_to_params = explode(".", $path_to_substr[count($path_to_substr) - 1], -1);
    if (count($file_to_params) <> 2) {
      throw new \Exception("File name is wrong! The locale is missing. File path given is <" . $path . ">", NULL, NULL);
    } else {
      return array("source" => $file_to_params[0], "locale" => $file_to_params[1]);
    }
  }

  private function storeContentsIntoArray($data, $params) {
    //TODO: escape < and > as they are forbidden character in the resource files.
    switch ($params["type"]) {
      case "common":
        foreach ($data as $element) {
          $this->res_common[$params["locale"]][$params["source"]][$element->getAttribute("key")] = $element->nodeValue;
        }
        if (!array_key_exists($params["source"], $this->res_common[$params["locale"]]))
          $this->res_common[$params["locale"]][$params["source"]] = array();
        break;
      case "local":
        foreach ($data as $element) {
          $this->res_local[$params["locale"]][$params["source"]][$element->getAttribute("key")] = $element->nodeValue;
        }
        break;
      default:
        throw new Exception("Type is wrong!!! common and local are the only values allowed", NULL, NULL);
        break;
    }
  }

  public function getCommonResource($common_source, $key) {
    if ($this->res_common) {
//      throw new Exception("No common resources found.", NULL, NULL);
    }

    if (isset($this->res_common[$this->app->locale][$common_source][$key])) {
      return $this->res_common[$this->app->locale][$common_source][$key];
    } else {
      return $this->res_common['en'][$common_source][$key];
    }

    return null;
  }

  /**
   * Return a resource based on the page name and the key given (/Resources/Local/Page.lang.xml)
   * 
   * @param string $local_source
   * @param string $key
   * @return string
   */
  public function getLocalResource($page_name, $key) {
    if (array_key_exists($page_name, $this->res_local[$this->app->locale])) {
      return $this->res_local[$this->app->locale][$page_name][$key];
    } else {//always display placeholder for missing locale resource
      return (array_key_exists($page_name, $this->res_local[$this->app->context->defaultLang])) ?
          $this->res_local[$this->app->context->defaultLang][$page_name][$key] :
          'Missing resource: {' . $this->app->locale . '}{' . $page_name . '}{' . $key . '}';
      //return 'Missing resource: {'.$this->app->locale.'}{'.$page_name.'}{'.$key.'}';
    }

    return null;
  }

  /**
   * Return an array of resources based on the page name given (/Resources/Local/Page.lang.xml)
   * 
   * @param string $page_name
   * @return array
   */
  public function getLocalResourceArray($page_name) {
    if (isset($this->res_local[$this->app->locale][$page_name])) {
      return (array_key_exists($page_name, $this->res_local[$this->app->locale])) ?
          $this->res_local[$this->app->locale][$page_name] :
          $this->getCommonResourceArray($page_name);
    } else {//always display placeholder for missing locale resource
      return (array_key_exists($page_name, $this->res_local[$this->app->context->defaultLang])) ?
          $this->res_local[$this->app->context->defaultLang][$page_name] :
          $this->getCommonResourceArray($page_name);
    }
  }

  /**
   * Return an array of resources based on the page name given (/Resources/Local/Page.lang.xml)
   * 
   * @param string $page_name
   * @return array
   */
  public function getCommonResourceArray($page_name) {
    if (array_key_exists($page_name, $this->res_common[$this->app->locale])) {
      return $this->res_common[$this->app->locale][$page_name];
    } else {//always display placeholder for missing locale resource
      return $this->res_common[$this->app->context->defaultLang][$page_name];
    }
  }

}
