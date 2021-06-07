<?php

namespace SinanBekar\Cloudflare;

use \SinanBekar\Cloudflare\Exceptions\UnexpectedIpRanges;
use \SinanBekar\Cloudflare\Helpers;

class Operations
{
    protected static $cfIpRanges = [
        '173.245.48.0/20',
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '141.101.64.0/18',
        '108.162.192.0/18',
        '190.93.240.0/20',
        '188.114.96.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',
        '162.158.0.0/15',
        '172.64.0.0/13',
        '131.0.72.0/22',
        '104.16.0.0/13',
        '104.24.0.0/14'
    ];

    private static $_instance = null;

    private function __construct () { }

    /**
     * Gets an instance of this singleton. If no instance exists, a new instance is created and returned.
     * If one does exist, then the existing instance is returned.
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Get defined ip address ranges.
     * @return array
     */
    public function getCfIpRanges()
    {
        return self::$cfIpRanges;
    }

    /**
     * Set ip ranges
     * @param array $ipRanges
     */
    public function setCfIpRanges($ipRanges)
    {
        if (!is_array($ipRanges) || empty($ipRanges)) {
            throw new UnexpectedIpRanges( "IP ranges must be array and not empty.");
        } else {
            foreach ($ipRanges as $range) {
                [$address, $netmask] = explode('/', $range, 2);
                $netmask = (int)$netmask;
                if ($netmask < 0 || $netmask > 32) {
                    throw new UnexpectedIpRanges("$range is not valid IP range.");
                }
                if (false === ip2long($address)) {
                    throw new UnexpectedIpRanges("$range is not valid IP range.");
                }
            }
        }
        self::$cfIpRanges = $ipRanges;
    }

    /**
     * Checks HTTP Headers for prevent spoofing.
     * @return bool
     */
    public function isCorrectCfHeaders()
    {
        if (!isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return false;
        }
        if (!isset($_SERVER['HTTP_CF_RAY'])) {
            return false;
        }
        if (!isset($_SERVER['HTTP_CF_VISITOR'])) {
            return false;
        }

        return true;
    }

    /**
     * Checks if is Cloudflare active and prevents spoofing.
     * @return bool
     */
    public function isCloudflare()
    {
        $isCfIp = false;
        foreach ($this->getCfIpRanges() as $range) {
            if (Helpers::isIpInRange($_SERVER['REMOTE_ADDR'], $range)) {
                $isCfIp = true;
                break;
            }
        }
        return ($this->isCorrectCfHeaders() && $isCfIp);
    }

    /**
     * Get client IP address. 
     * Can be used Restore::getIp as a shorthand if no need to adjustment.
     */
    public function getClientIpAddress()
    {
        if ($this->isCloudflare()) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
