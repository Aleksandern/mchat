<?php

class Nick
{
    private $nick_mod;
    private $ip;

    function __construct()
    {
        $this->nick_mod = new NickMod();
        $this->ip = Ip::get();

    }

    public function add($nick, $profile = '', $site = false)
    {
        $nick_info = $this->nick_mod->info($nick);

        if (empty($nick_info)) {
            $nick_info['ip_addr'] = $this->ip;
            $nick_info['nick'] = $nick;
            $nick_info['profile'] = $profile;                
            $this->nick_mod->add($nick_info, $site);

        } else {
            if ($site) {
                $nick_info['ip_addr'] = $this->ip;                
                $nick_info['profile'] = $profile;                
                $this->nick_mod->update($nick_info, $site);
            } else {
                if ($nick_info['site']) {
                    if ($nick_info['ip_addr'] == $this->ip) {
                        $this->nick_mod->update($nick_info, true);
                    } else {
                        // genNick
                        $nick_info = $this->gen($nick);
                    }
                } else {
                    if ($nick_info['ip_addr'] == $this->ip) {
                        // update
                        $this->nick_mod->update($nick_info, false);
                    } else {
                        // genNick
                        $nick_info = $this->gen($nick);
                    }
                }
            }
        }
        return $nick_info;
    }

    public function gen($nick)
    {
        do {
            $nick = $nick.mt_rand(1, 1000);
        } while ($this->nick_mod->check($nick));

        $nick_info['nick'] = $nick;
        $nick_info['ip_addr'] = $this->ip;        
        $nick_info['profile'] = '';        
        $nick_add = $this->nick_mod->add($nick_info);
        $nick_info['id'] = $nick_add;        

        return $nick_info;
    }
}
