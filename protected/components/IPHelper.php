<?php

//Created from http://stackoverflow.com/questions/444966/working-with-ipv6-addresses-in-php

class IPHelper extends CComponent {

    static function GetRealRemoteIp($binary_format = true) {
        $Ip = '0.0.0.0';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '')
            $Ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
            $Ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '')
            $Ip = $_SERVER['REMOTE_ADDR'];
        if (($CommaPos = strpos($Ip, ',')) > 0)
            $Ip = substr($Ip, 0, ($CommaPos - 1));

        return (!$binary_format ? $Ip : self::IPToBin($Ip));
    }

    static function IPToBin($ip) {
        return inet_pton($ip);
    }

    static function BinToIP($number) {
        return inet_ntop($number);
    }

}

?>
