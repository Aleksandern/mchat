<?php

class NickMod
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

    public function check($nick)
    {
        $req = $this->db->count("SELECT COUNT(*) FROM `".$this->tables['users']."` WHERE nick=? ", $nick);
        return $req;
    }

    public function update($nick, $site = false)
    {
        if ($site) $site = 1;
        else $site = 0;
        $date = Date::get();
        $data = Array($nick['profile'], $date, $nick['ip_addr'], $site, $nick['id']);
        $this->db->query("UPDATE `".$this->tables['users']."` SET profile=?, date=?, ip_addr=?, site=? WHERE id=?", $data);
    }

    public function info($nick)
    {
        if (is_numeric($nick)) {
            $req = $this->db->select("SELECT * FROM `".$this->tables['users']."` WHERE id=?", $nick, 1);
        } else {
            $req = $this->db->select("SELECT * FROM `".$this->tables['users']."` WHERE nick=?", $nick, 1);
        }

        return $req;
    }

    public function add($nick, $site = false)
    {
        if ($site) $site = 1;
        else $site = 0;
        $date = Date::get();
        $data = Array($nick['nick'], $nick['profile'], $date, $nick['ip_addr'], $site);
        $res = $this->db->insert("INSERT INTO `".$this->tables['users']."` SET nick=?, profile=?, date=?, ip_addr=?, site=?", $data);
        return $res;
    }
}
