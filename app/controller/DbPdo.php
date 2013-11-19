<?php

class DbPdo
{
    private $conn;
    private $select;
    private $host;
    private $user;
    private $pass;
    private static $mode_res = 2; // 1 - для запроса одной строки (LIMIT 1), 2 - возвращает именованный массив
                     
    function __construct($db, $host, $user, $pass) 
    {
        $this->select = $db;        
        $this->host = $host;  
        $this->user = $user;
        $this->pass = $pass;
        if ($db == '') $dbname = '';
        else $dbname = ";dbname=".$this->select;

      
        try {
            $this->conn = new PDO ("mysql:host=".$this->host.$dbname."", $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            //$this->conn->exec('SET NAMES UTF8');
        }
        catch (PDOException $e) {
            echo $e->GetMessage().' '.FILE_APPEND;
        }
    }

    public function count($request, $data = Array())
    {
        $query = $this->query($request, $data);
        $count = $query->fetch(PDO::FETCH_NUM);
        return $count[0];
    }

    public function query($request, $data = Array()) 
    {
        $data = $this->strToArr($data);
        $query = $this->conn->prepare($request); 
        $query->execute($data);
        return $query;
    }
    
    public function select($request, $data = Array(), $mode_res = 2) 
    {
        $data = $this->strToArr($data);
        $query = $this->conn->prepare($request);
        $query->execute($data);
        return $this-> resultToArray($query, $mode_res);
    }

    public function insert ($request, $data) 
    {
        $data = $this->strToArr($data);
        $query = $this->conn->prepare($request);
        $query->execute($data);
        return $this->conn-> lastInsertId();
    }
  
    private function resultToArray(&$query, $mode_res = 2)
    {
        $result = array();
        if($query === false) { return $result; }
      
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $result = $query->fetchAll();

        if (($mode_res == 1) && (!empty($result))) $result = $result[0];
        
        return $result;
    }     

    private function strToArr ($data)
    {
        if (!is_array($data)) {
            $data = str_replace (' ', '', $data);
            $data = explode(',', $data);
        }
        return $data;
    }

    public function quoteIN($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $val = $this->conn->quote($val);
                $data[$key] = $val;
            }
            $data = implode(',', $data);
        } else {
            $data = explode (',',$data);
            $data = $this->quoteIN($data);
        }

        if (empty($data)) {
            $data = 'NULL';
        }

        return $data;
    }
}
