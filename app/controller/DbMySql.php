<?php

class DbMySql
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
      
        $this->conn = mysql_connect($this->host, $this->user, $this->pass) OR DIE("Can't connect to the base.");
        
        if ($db != '') {
            mysql_select_db($this->select, $this->conn) or die(mysql_error());
        }
        mysql_query("SET NAMES UTF8",$this->conn);

    }

    public function count($request, $data = Array())
    {
        $req = $this->query($request, $data);
        $res = mysql_result($req, 0);
        return $res;
    }

    public function query($request, $data = Array()) 
    {
        $request = $this->prepare($request, $data);
        $res = mysql_query ($request, $this->conn) or die("Error request '{$request}'");
        return $res;
    }
    
    public function select($request, $data = Array(), $mode_res = 2) 
    {
        $request = $this->prepare($request, $data);
        $query = mysql_query ($request, $this->conn) or die("Error request '{$request}'");
        return $this-> resultToArray($query, $mode_res);
    }

    public function insert ($request, $data) 
    {
        $request = $this->prepare($request, $data);
        mysql_query ($request, $this->conn) or die("Error request '{$request}'");
        return mysql_insert_id();
    }
  
    private function resultToArray(&$query, $mode_res = 2)
    {
        $result = array();
        $i = 0;
        if($query === false) { return $result; }

        while($row = mysql_fetch_array($query)) {
            $result[$i] = array();
            foreach($row as $key=>$value) {
                if(!is_numeric($key)) { $result[$i][$key] = $value; }
            }
            $i++;
        }

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

    private function prepare($request, $data)
    {
        $data = $this->strToArr($data);
        $data = $this->quote($data);

        if (!empty($data)) {
            $symb_arr = explode("?", $request);
            $request_ed = '';
            $search = Array();
            for ($i = 0; $i < count($symb_arr); $i++) {
                if ($i == count($symb_arr)) {
                    $request_ed .= $symb_arr[$i];
                } else {
                    $request_ed .= $symb_arr[$i].'{'.$i.'}';
                    $search[] = '{'.$i.'}';
                }
            }

            $request = str_replace($search,$data,$request_ed); 
        }

        return $request;
    }

    private function quote($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = "'".mysql_real_escape_string($val)."'";
            }
        } else {
            $data = "'".mysql_real_escape_string($data)."'";
        }

        return $data;
    }

    public function quoteIN($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $val = $this->quote($val);
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
