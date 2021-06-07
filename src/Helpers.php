<?php

namespace SinanBekar\Cloudflare;

class Helpers {

    public static function isIpInRange($ip, $range)
    {
        [$address, $netmask] = explode('/', $range, 2);
        $netmask = (int)$netmask;
        if ($netmask < 0 || $netmask > 32) {
            return false;
        }
        if (false === ip2long($address)) {
            return false;
        }
        return 0 === substr_compare(sprintf('%032b', ip2long($ip)), sprintf('%032b', ip2long($address)), 0, $netmask);
    }

}