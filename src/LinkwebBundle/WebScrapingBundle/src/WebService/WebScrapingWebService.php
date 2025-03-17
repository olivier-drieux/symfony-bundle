<?php

namespace App\LinkwebBundle\WebScrapingBundle\src\WebService;

use Linkweb\Utils\UserAgent;
use Linkweb\WebService\AbstractWebService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class WebScrapingWebService extends AbstractWebService
{
    /**
     * WebScrapingWebService constructor.
     *
     * @param string $proxyCommon autowired
     * @param string $proxyGoogle autowired
     * @param string $proxyGouv   autowired
     */
    public function __construct(
        #[Autowire(env: 'PROXY_COMMON')]
        private string $proxyCommon,
        #[Autowire(env: 'PROXY_GOOGLE')]
        private string $proxyGoogle,
        #[Autowire(env: 'PROXY_GOUV')]
        private string $proxyGouv,
    ) {
        // Mandatory call to parent constructor
        parent::__construct();
    }

    /**
     * @param string $uri
     * @param string $device
     * @param array  $options default set to []
     * @param bool   $unsafe  default set to false
     * @param string $method  default set to 'GET'
     *
     * @return ResponseInterface
     *
     * @throws ExceptionInterface
     */
    public function commonRequest(string $uri, string $device, array $options = [], bool $unsafe = false, string $method = 'GET'): ResponseInterface
    {
        // A new client is set at each request to ensure a new proxy
        $this->setNewClient([
            // Timeout the request after 10 seconds
            'timeout' => 10,
            // Use of datacenter proxy to avoid being recognized as a robot
            'proxy' => "http://$this->proxyCommon",
            'headers' => [
                // User-Agent header to minimize data transfer on given device
                'User-Agent' => UserAgent::minimizeData($device),
            ],
        ]);

        return $this->request($method, $uri, $options, $unsafe);
    }

    /**
     * @param string $uri
     * @param string $device
     * @param array  $options default set to []
     * @param bool   $unsafe  default set to false
     *
     * @return ResponseInterface
     *
     * @throws ExceptionInterface
     */
    public function googleRequest(string $uri, string $device, array $options = [], bool $unsafe = false): ResponseInterface
    {
        // A new client is set at each request to ensure a new proxy
        $this->setNewClient([
            // Timeout the request after 10 seconds
            'timeout' => 10,
            // Use of residential proxy to bypass Google captcha and block
            'proxy' => "http://$this->proxyGoogle",
            'headers' => [
                // Cookie is required in order to bypass the Google consent page
                'Cookie' => 'SOCS=CAESNQgCEitib3FfaWRlbnRpdHlmcm9udGVuZHVpc2VydmVyXzIwMjMwNTA5LjA1X3AxGgJmciAEGgYIgI-LowY',
                // User-Agent header to minimize data transfer on given device
                'User-Agent' => UserAgent::minimizeData($device),
            ],
        ]);

        return $this->request('GET', $uri, $options, $unsafe);
    }

    /**
     * @param string $uri
     * @param string $device
     * @param array  $options default set to []
     * @param bool   $unsafe  default set to false
     *
     * @return ResponseInterface
     *
     * @throws ExceptionInterface
     */
    public function gouvRequest(string $uri, string $device, array $options = [], bool $unsafe = false): ResponseInterface
    {
        // A new client is set at each request to ensure a new proxy
        $this->setNewClient([
            // Timeout the request after 10 seconds
            'timeout' => 10,
            // Use of residential proxy to bypass Google captcha and block
            'proxy' => "http://$this->proxyGouv",
            'headers' => [
                // User-Agent header to minimize data transfer on given device
                'User-Agent' => UserAgent::minimizeData($device),
            ],
        ]);

        return $this->request('GET', $uri, $options, $unsafe);
    }
}
