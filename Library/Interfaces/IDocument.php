<?php

namespace Library\Interfaces;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

interface IDocument {
  //set webpath which is created in DAL
  public function setWebPath($webPath);

  public function setAbsolutePath($absolutePath);
  //setter for filename, in this case we don't have to use filename as table field
  public function setFilename($filename);
  //setter for contentsize, in this case we don't have to use contentsize as table field
  public function setContentSize($size);
  //setter for contenttype, in this case we don't have to use contenttype as table field
  public function setContentType($contentType);
  //check for valid extensions, return array of extension strings if we need the check or false if we don't
  public function ValidExtensions();

  public function WebPath();

  public function AbsolutePath();

  public function Filename();

  public function ContentSize();

  public function ContentType();

}