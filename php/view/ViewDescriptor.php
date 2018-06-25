<?php

class ViewDescriptor {
    private $title;
    private $errorMessages;
    private $navigationBar;
    private $content;
    private $subpage;
    private $page;
    private $scripts;
    private $isJson;

    public function __construct() {
        $this->errorMessages = array();
        $this->scripts = array();
        $isJson = false;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getErrorMessages() {
        return $this->errorMessages;
    }

    public function getNavigationBar() {
        return $this->navigationBar;
    }

    public function getContent() {
        return $this->content;
    }
    
    public function getSubpage() {
        return $this->subpage;
    }
    
    public function getPage() {
        return $this->page;
    }
    
    public function &getScripts() {
        return $this->scripts;
    }
    
    public function isJson() {
        return $this->isJson;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function addErrorMessage($value) {
        $this->errorMessages[] = $value;
    }

    public function setNavigationBar($navigationBar) {
        $this->navigationBar = $navigationBar;
    }

    public function setContent($content) {
        $this->content = $content;
    }
    
    public function setSubpage($subpage) {
        $this->subpage = $subpage;
    }
    
    public function setPage($page) {
        $this->page = $page;
    }
    
    public function addScript($script) {
        $this->scripts[] = $script;
    }
    
    public function toggleJson() {
        $this->isJson = true;
    }
}
