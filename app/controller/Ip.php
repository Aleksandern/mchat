<?php

class Ip
{
    public static function get()
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else $ip = '127.0.0.1';
        return $ip;
    }
}
