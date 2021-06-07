# cf-restore-visitor-ip
Cloudflare Restore Original Visitor IP in PHP

## There are mod_cloudflare(mod_remoteip),nginx_realip packages why would we use this ?

mod_cloudflare hasn't been updated for years. Cloudflare's IP ranges can be change in time (Current ranges are different). 
mod_cloudflare is only for Apache server.
If you're using shared hosting (and if the server administrator does not configure cloudflare rules) mostly you can't change core configs.

If you have access to server configuration files and have experience configuring them, please check mod_remoteip(Apache), ngx_http_realip_module(NGINX) first. It should be better to restore the log IP addresses. 

[Cloudflare mod_remoteip Article](https://support.cloudflare.com/hc/en-us/articles/200170786-Restoring-original-visitor-IPs#C5XWe97z77b3XZV)

[Cloudflare ngx_http_realip_module Article](https://support.cloudflare.com/hc/en-us/articles/200170786-Restoring-original-visitor-IPs#JUxJSMn3Ht5c5yq)


## Notes and Good to Know Info

Does not detect if the client connects via proxy or vpn. Based on HTTP_CF_CONNECTING_IP and prevents spoofing if Cloudflare is not active. Returns REMOTE_ADDR if Cloudflare is not active.


> **_Warning:_**  For now only IPv4

> **_Warning:_**  Not recommended for large production applications for not restoring logging IPs as mentioned above.


## Installation

Composer (recommended)
```
composer require sinanbekar/cf-restore-visitor-ip
```



# Usage

```php
// Shorthand Usage:
$ipAddress = \SinanBekar\Cloudflare\Restore::getIp();
```

Setting IP ranges (if there are no changes no need to use you can use shorthand)
```php
$ipRanges = ['173.245.48.0/20','103.21.244.0/22','103.22.200.0/22'...];
$cf = \SinanBekar\Cloudflare\Operations::getInstance();
$cf->setCfIpRanges($ipRanges);

$ipAddress = $cf->getClientIpAddress(); // Or \SinanBekar\Cloudflare\Restore::getIp();
```
Other Methods:
```php
\SinanBekar\Cloudflare\Operations::getInstance()->getCfIpRanges(); // @return array

\SinanBekar\Cloudflare\Operations::getInstance()->isCorrectCfHeaders(); // @return bool

\SinanBekar\Cloudflare\Operations::getInstance()->isCloudflare(); // @return bool

\SinanBekar\Cloudflare\Helpers::isIpInRange($ip, $range); // @return bool

// Check function comments for more explanation

```

## Contributing

Please feel free to contribute.





