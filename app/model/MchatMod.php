<?php

class MchatMod
{
    private $db;
    private $config;
    private $tables;

    function __construct()
    {
        $this->db = new Db();
        $this->config = Config::get();
        $this->tables = $this->config['tables'];
    }

}
