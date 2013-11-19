<?php

class Db
{
    private $db;
    private $config;

    function __construct($db = true) 
    {
        $this->config = Config::get();

        if (!$db) $basename = '';
        else $basename = $this->config['db']['name'];
        $host = $this->config['db']['host'];
        $user = $this->config['db']['user'];
        $pass = $this->config['db']['pass'];

        if (extension_loaded('pdo') && extension_loaded('pdo_mysql')){
            //echo 'pdo';
            $this->db = new DbPdo($basename, $host, $user, $pass);
        } else {
            if  (function_exists('mysql_connect')){
                //echo 'mysql';
                $this->db = new DbMySql($basename, $host, $user, $pass);
            } else {
                die ('{"status" : "2", "msg" : "Error DB!"}');
            }
        }
    }

    public function __call ($method, $args)
    {
        return call_user_func_array(array($this->db, $method), $args);
    }
}
