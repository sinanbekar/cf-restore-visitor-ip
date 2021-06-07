<?php

namespace SinanBekar\Cloudflare;

class Restore
{
    /**
     * Shorthand form for Operations::getInstance()->getClientIpAddress()
     * Use if no need to adjustment.
     */
    public static function getIp()
    {
        return Operations::getInstance()->getClientIpAddress();
    }
}
