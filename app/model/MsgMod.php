<?php

class MsgMod
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
    
    public function add($nick, $text)
    {
        $date = Date::get();
        $data = Array($nick['id'], $text, $date);
        $this->db->insert("INSERT INTO `".$this->tables['msgs']."` SET id_nick=?, textmsg=?, date=? ", $data);
    }

    public function listAll($msgs_id = '')
    {
        $msgs = $this->tables['msgs'];
        $users = $this->tables['users'];
        if (!empty($msgs_id)) {
            $data_in = $this->db->quoteIN($msgs_id);
            $query_add = "WHERE NOT $msgs.id IN (".$data_in.")";
        } else $query_add = '';
        //$req = $this->db->select("SELECT * FROM `".$this->tables['msgs']."` ");
        $req = $this->db->select("SELECT $msgs.id, $msgs.id_nick, $msgs.textmsg, $msgs.date, $users.nick FROM `$msgs` LEFT JOIN `$users` ON $msgs.id_nick=$users.id ".$query_add." ORDER BY $msgs.date ASC ");
        return $req;
    }

    
}
