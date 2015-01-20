<?php

namespace Library;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

abstract class Manager {

    protected $dao;
    protected $filters;


    public function __construct($dao, $filters) {
        $this->dao = $dao;
        $this->filters = $filters;
    }

}
