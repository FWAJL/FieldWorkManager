<?php

namespace Library;

class Page extends ApplicationComponent {
    protected $contentFile;
    protected $vars = array();
    
    public function addVar($key, $value)
    {
        if(!is_string($key) || is_numeric($key) || empty($key)) {
            throw new \InvalidArgumentException('Key name must be a string not null');
        }
        $this->vars[$key] = $value;
    }
    
    public function getGeneratedPage() {
        if (!file_exists($this->contentFile)) {
            throw new \RuntimeException('The view '.$this->contentFile.' doesn\'t exist.');
        }
        $user = $this->app->user();
        
        $int = extract($this->vars);
        
        ob_start();
        require $this->contentFile;
        $content = ob_get_clean();
        
        ob_start();
//        require __ROOT__.Enums\FolderName::AppsFolderName.$this->app->name().Enums\FileNameConst::LayoutTemplate;
        require __ROOT__.Enums\FolderName::AppsFolderName.$this->app->name().Enums\FileNameConst::HeaderTemplate;
        require __ROOT__.Enums\FolderName::AppsFolderName.$this->app->name().Enums\FileNameConst::ContenTemplate;
        require __ROOT__.Enums\FolderName::AppsFolderName.$this->app->name().Enums\FileNameConst::FooterTemplate;
        return ob_get_clean();
    }
    
    public function setContentFile($contentFile) {
        if(!is_string($contentFile) || empty($contentFile)) {
            throw new \InvalidArgumentException('The view '.$contentFile.' doesn\'t exist.');
        }
        $this->contentFile = $contentFile;
    }
}