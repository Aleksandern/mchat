<?php

class Mchat
{
    private $mchat_mod;
    private $nick;
    private $msg;

    function __construct()
    {
        $this->mchat_mod = new MchatMod();
        $this->nick = new Nick();
        $this->msg = new Msg();
    }

    public function dataProc()
    {
        $token = new Token();
        $token->check();
        $mchat = GetInp::gp('mchat');

        if ($mchat == 'msg') {
            $text = GetInp::gp('mch_text');
            $nick = GetInp::gp('mch_nick');        
            $nick_info = $this->nick->add($nick);
            
            $this->msg->add($nick_info, $text);
            
            Resp::send();
        }

        if ($mchat == 'list') {
            $msgs_id = GetInp::gp('msgsid');
            $nick = GetInp::gp('mch_nick');        
            $nick_info = $this->nick->add($nick);

            $msgs_all = $this->msg->listAll();
            $msgs_id_arr = explode(',', $msgs_id);
            $resp['msgs_del'] = array_diff ($msgs_id_arr, $msgs_all['msgs_id']);
            $resp['nick_del'] = $msgs_all['nick_del'];
            $resp['nick_del'] = array_unique($resp['nick_del']);

            $resp['nick'] = $nick_info['nick'];

            $msgs_list_all = $this->msg->listAll($msgs_id);
            $resp['msgs'] = $msgs_list_all['msgs'];

            Resp::send($resp);
        }

        $msgs = $this->msg->listAll('2,3');
        $resp['msgs'] = $msgs['msgs'];
        $resp['nick'] = '123';


            $msgs_all = $this->msg->listAll();
            $msgs_id_arr = explode(',', '41,3');
            $resp['msgs_del'] = array_diff ($msgs_id_arr, $msgs_all['msgs_id']);
            $resp['nick_del'] = $msgs_all['nick_del'];

            print_r($resp);

        Resp::send($resp);

        Resp::send('Skipped!');
    }

}
