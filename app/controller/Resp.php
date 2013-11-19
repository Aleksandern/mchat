<?php

class Resp
{
    public static function send($info = Array()) 
    {
        if (is_array($info)) {
            if (!empty($info)) {
                //print_r ($info);
                $msgs = json_encode($info['msgs']);
                $msgs_del = json_encode($info['msgs_del']);
                $nick_del = json_encode($info['nick_del']);
                $nick = $info['nick'];
                $time = Date::time();

                die ('{"status" : "0", "nick": "'.$nick.'","msgs" : '.$msgs.', "msgs_del" : '.$msgs_del.', "nick_del" : '.$nick_del.',"time" : "'.$time.'"}');
            } else {
                die ('{"status" : "1"}');
            }
        } else {
            die ('{"status" : "2", "msg" : "'.$info.'"}');
        }
    }
}
