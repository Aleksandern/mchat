<?php

class UserAgent
{
    public static function get()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $res = $_SERVER['HTTP_USER_AGENT'];
        } else $res = 'eprst';
        return $res;
    }
}
