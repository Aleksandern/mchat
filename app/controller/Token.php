<?php

class Token
{
    private $config;

    function __construct()
    {
        $this->config = Config::get();        
    }
    
    public function gen()
    {
        if (isset($_SESSION)) $sess_id = session_id();
        else $sess_id = '';

        $data = $sess_id.Ip::get().UserAgent::get();
        $salt = site_path.hash('md5', $this->config['token_salt'].$this->config['db']['pass']);
        $token = hash ('sha256', $data.$salt);
        return $token;
    }

    public function check()
    {
        if ($this->config['token']) {
                $token = GetInp::gp('mch_token');
                $token_gen = $this->gen();
                if ($token != $token_gen) Resp::send('Token is wrong!');
        }
    }
}
