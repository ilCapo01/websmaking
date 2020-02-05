<?php

// TODO: 
// Func to add plugin management page to panel.
class Plugin {
    var $author = 'WebsMaking.com';
    var $name = 'Untitled';
    var $version = '0.0.1';
    
    var $baseDir;
    
    var $pages = array();
    
    function Plugin($name, $author) {
        $this->name = $name;
        $this->author = $author;
        $this->baseDir = dirname(__FILE__).'/plugins/';
        
        include $this->baseDir.$this->name.'.php';
        $baseObj = new $this->name;
        $baseObj->onLoad();
    }
    
    // Called everytime the plugin is being enabled \ loaded.
    function onLoad() {}
    
    // Called everytime the plugin is being disabled or removed.
    function onRemove() {}
    
    function getString() {
        return 'Plugin '.$this->name.'('.$this->version.') was written by '.$this->author;
    }
    
    function addPanelPage($title, $pageFilename) {
        //$count = (count($this->pages) ? count($this->pages) : 0);
        if (!isset($this->pages) || is_null($this->pages))
            return false;
        array_push($this->pages, array('title' => $title, 'htmlFilename' => $pageFilename));
        return true;
    }
    
    function getPanelPage($pageIndex) {
        $count = (count($this->pages) ? count($this->pages) : 0);
        
        if ($count > $pageIndex) {
            $html = $this->pages[$pageIndex]['htmlFilename'];
            if (!file_exists($html))
                return false;
            include $html;
            return true;
        }
        return false;
    }
}


?>