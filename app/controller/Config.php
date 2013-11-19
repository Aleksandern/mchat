<?php

class Config 
{
    private static $config;
    private $props = array ();   
    private static $instance;
  
    private function __construct(){ /* ... @return Singleton */ }
    private function __clone()    { /* ... @return Singleton */ }
    private function __wakeup()   { /* ... @return Singleton */ }

    public static function get () 
    { 
        if (empty(self::$instance)) {
            self::$instance = new Config ();
        }
        self::get_file();
        return self::$config;
    }

    public static function get_file() 
    {
        if (!self::$config) {
            self::$config = include(site_path.DIRSEP.'config.php');
            self::$config['host'] = $_SERVER['HTTP_HOST'];
        }
    }
}
