<?php

class GetInp
{
    public static function gp($gp)
    {
        $gp_res = self::has($gp);
        if ($gp_res) {
            $gp_res = $_POST[$gp];
            //$gp_res = $_GET[$gp];
            $gp_res = htmlspecialchars($gp_res);
            $gp_res = trim($gp_res);
        } else {
            $gp_res = '';
        }

        return $gp_res;
    }

    public static function has($gp)
    {
        if (isset($_POST[$gp])) return true;
        //if (isset($_GET[$gp])) return true;
        return false;
    }
}

