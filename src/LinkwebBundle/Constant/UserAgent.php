<?php

namespace App\LinkwebBundle\Constant;

/**
 * Class UserAgent.
 */
readonly class UserAgent
{
    // List of some desktop User-Agent headers
    final public const DESKTOP = [
        // The first one might be used to minimize data transfer
        'Mozilla/5.0 (X11; U; Linux x86_64; fr; rv:1.7.12) Gecko/20060202 CentOS/1.0.7-1.4.3.centos4 Firefox/1.0.7',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:79.0) Gecko/20100101 Firefox/79.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Safari/605.1.15',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.5646.44 Safari/537.36 Edg/85.0.564.44',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36 OPR/67.0.3575.137',
    ];

    // List of some mobile User-Agent headers
    final public const MOBILE = [
        // The first one might be used to minimize data transfer
        'Mozilla/5.0 (Android; Mobile; rv:20.0) Gecko/20.0 Firefox/20.0',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (Linux; Android 9; SM-G960F Build/PPR1.180610.011; rv:68.0) Gecko/68.0 Firefox/68.0',
        'Mozilla/5.0 (Linux; Android 10; Pixel 3XL Build/QQ3A.200605.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
        'Mozilla/5.0 (iPad; CPU OS 13_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (Linux; Android 9; LYA-L29 Build/HUAWEILYA-L29) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Mobile Safari/537.36',
    ];
}
