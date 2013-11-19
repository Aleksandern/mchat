<?php

class Msg
{
    private $msg_mod;

    function __construct()
    {
        $this->msg_mod = new MsgMod();
    }

    public function add($nick_info, $text)
    {
        $this->msg_mod->add($nick_info, $text);
    }

    public function listAll($msgs_id = '')
    {
        $list = $this->msg_mod->listAll($msgs_id);
        $list = $this->prepList($list);

        return $list;
    }

    private function prepList($msgs)
    {
        $res['msgs'] = Array();
        $res['msgs_id'] = Array();
        $res['nick_del'] = Array();

        foreach ($msgs as $key => $val) {
            $nick = $val['nick'];
            if (empty($nick)) {
                $val['nick'] = 'Deleted';
                $res['nick_del'][] = $val['id_nick'];
            }
            $val['date'] = Date::full($val['date']);

            $res['msgs'][$key] = $val;
            $res['msgs_id'][] = $val['id'];
        }
        return $res;
    }

    public function listHtml($list)
    {
        $html = '';
        foreach ($list as $key => $val) {
            $date = $val['date'];
            //if (empty($val['nick'])) $val['nick'] = 'Deleted';
            $html .= "
                <div class='mch-msg' data-id='".$val['id']."'>
                    <div class='mch-date'>".$date."</div>
                    <div><a href='javascript:void();' class='mch-nick' data-id='".$val['id_nick']."'>".$val['nick']."</a></div>
                    <div class='mch-text'>".$val['textmsg']."</div>
                </div>
                ";
        }

        return $html;
    }

}
