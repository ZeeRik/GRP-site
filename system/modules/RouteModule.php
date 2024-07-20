<?php

class Route {
    public $uri = array();
    private static $_staticUri = array();
    
    public function __construct() {
        $parseUrl = parse_url($_SERVER['REQUEST_URI']);
        $uri = explode('/', $parseUrl['path']);
        $count = count($uri);

        for ($i = 0; $i < $count; $i++) {
            if (isset($uri[$i]) && strlen($uri[$i]) > 0) {
                $array[] = $uri[$i];
            }
        }

        $this->uri = $array;
        self::$_staticUri = $array;
    }
    
    public function getArray() {
        return self::$_staticUri;
    }
}
