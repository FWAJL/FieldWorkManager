<?php

namespace Applications\PMTool\Controllers;

class AboutController extends \Library\BaseController {

  public function executeIndex(\Library\HTTPRequest $request) {
    //TODO: add resource using a Resource manage
    $this->page->addVar('title', 'About');
    
    $resourceFileKey = "about";

    $this->page->addVar('h3_title', $this->app->i8n->getLocalResource($resourceFileKey,"h3_title"));
    $this->page->addVar('paragraph_1', $this->app->i8n->getLocalResource($resourceFileKey,"paragraph_1"));
    $this->page->addVar('paragraph_2', $this->app->i8n->getLocalResource($resourceFileKey,"paragraph_2"));
    $this->page->addVar('paragraph_3', $this->app->i8n->getLocalResource($resourceFileKey,"paragraph_3"));
    $this->page->addVar('paragraph_4', $this->app->i8n->getLocalResource($resourceFileKey,"paragraph_4"));
    $this->page->addVar('resume_link_text', $this->app->i8n->getLocalResource($resourceFileKey,"resume_link_text"));
    $this->page->addVar('resume_url', $this->app->router->pageUrls[\Library\Enums\ResourceKeys\PublicPageUrls::ResumeUrl]);    
  }

}