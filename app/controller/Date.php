<?php

class Date
{
    private static $timezone = 0; // + hour

    public static function get()
    {
        $timezone = self::$timezone * 3600;
        $date = time() + $timezone;
        return $date;
    }

    public static function time($time = '')
    {
        if ($time == '') $time = self::get();

        $date = date("g:i a", $time);        
        return $date;
    }

    public static function full($time = '')
    {
        if ($time == '') $time = self::get();        

        $date = date("g:ia, M j", $time);        
        return $date;
    }
}
