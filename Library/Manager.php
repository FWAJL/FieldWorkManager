<?php

namespace Library;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

abstract class Manager {

    protected $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

}
