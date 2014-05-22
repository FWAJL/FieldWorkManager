<?php
/**
 * XmlLoader class
 * 
 * @author Jeremie Litzler
 * @copyright (c) 2014, Jeremie Litzler
 * @link http://jeremielitzler.net/Blog
 * 
 */
namespace Library\Utility;

class XmlLoader
{
    /**
     * path to xml file to load
     * 
     * @var string 
     */
    public $filePath = "";

    function __construct($filePath) {
        $this->filePath= $filePath;
    }
    
    public function Load() {
        $xml = NULL;
        if (file_exists($this->filePath)) {
            $xml = simplexml_load_file($this->filePath);
        } else {
            error_log('Failed to open '.$this->filePath);
        }
        return $xml;
    }
}