<?php

namespace App\LinkwebBundle\Constant;

class Proxy
{
    /** Smartproxy datacenter proxy price is $3.5 (3.4€) per month with 50Gb included */
    public const PROXY_COMMON_PRICE_PER_BITE = 3.4 / 50 / 1E9;
    /** Smartproxy residential proxy price is $7 (6.7€) per GByte (1E9 bytes) */
    public const PROXY_GOOGLE_PRICE_PER_BITE = 6.7 / 1E9;
    /** Webshare datacenter proxy price is $3 (2.9€) per month with 250GB included */
    public const PROXY_GOUV_PRICE_PER_BITE = 2.9 / 250 / 1E9;
    /** Maximum of attempts on the same request before giving up */
    public const PROXY_MAX_ATTEMPTS = 5;
}
