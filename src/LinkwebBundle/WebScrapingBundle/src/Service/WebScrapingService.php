<?php

namespace App\LinkwebBundle\WebScrapingBundle\src\Service;

use App\LinkwebBundle\WebScrapingBundle\src\Service\Traits\WebScrapingServiceTrait;
use App\WebService\WebScrapingWebService;
use Doctrine\ORM\EntityManagerInterface;
use Linkweb\Constant\Device;
use Linkweb\Exception\ApiException;
use Linkweb\Service\Interfaces\ServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class WebScrapingService.
 */
readonly class WebScrapingService
{
    use WebScrapingServiceTrait;

    /**
     * WebScrapingService constructor.
     *
     * @param LoggerInterface        $logger
     * @param TranslatorInterface    $translator
     * @param EntityManagerInterface $entityManager
     * @param WebScrapingWebService  $webScrapingWebService
     */
    public function __construct(
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
        private EntityManagerInterface $entityManager,
        private WebScrapingWebService $webScrapingWebService,
    ) {
        // Nothing to do here
    }

    /**
     * @param string $domainName
     * @param string $device
     *
     * @return array{results: int, price: float}
     *
     * @throws ApiException
     */
    public function getGoogleSearchDomainNumberOfResults(string $domainName, string $device): array
    {
        // Search the domain name on Google search with 'site:' prefix
        ['crawler' => $crawler, 'price' => $price] = $this->getGoogleSearchHtmlCrawler(
            "site:$domainName",
            $device,
            debugKey: 'google_search_number_of_results',
            extraQuery: ['num' => 100]
        );

        // Extract the number of results from the Google search page
        return [
            'results' => $crawler->filterXPath("//a[contains(@href, '$domainName')]/span[1]")->count(),
            'price' => $price,
        ];
    }

    /**
     * @param string $url
     *
     * @return array{isIndexed: bool, price: float}
     *
     * @throws ApiException
     */
    public function isUrlIndexed(string $url): array
    {
        // Search the URL on Google search with 'site:' prefix
        ['crawler' => $crawler, 'price' => $price] = $this->getGoogleSearchHtmlCrawler(
            "site:$url",
            Device::MOBILE,
            debugKey: 'google_search_is_indexed',
            extraQuery: ['num' => 1]
        );

        // Remove trailing slash from URL
        $trimmedUrl = \rtrim($url, '/');

        return [
            // Url is indexed if the XPath query for a div with id "search" contains a h3 element
            'isIndexed' => 0 < $crawler->filterXPath("//a[starts-with(@href, '/url?q=$trimmedUrl')][descendant::span]/@href")->count(),
            'price' => $price,
        ];
    }

    /**
     * @param string      $url
     * @param string      $keyword
     * @param string      $device
     * @param string|null $location
     * @param bool        $isExactMatch
     * @param bool        $withSerp     default set to false
     *
     * @return array
     *
     * @throws ApiException
     */
    public function getUrlKeywordPositions(string $url, string $keyword, string $device, string|null $location, bool $isExactMatch, bool $withSerp = false): array
    {
        // Search the keyword on Google search
        ['crawler' => $crawler, 'price' => $price] = $this->getGoogleSearchHtmlCrawler(
            $keyword,
            $device,
            $location,
            'google_search_url_keyword_positions',
            ['num' => 100]
        );

        // Parse the Google search result to get the URL's positions for the keyword
        $result = $this->parseGoogleResultForUrlKeywordPositions($url, $crawler, $isExactMatch, $withSerp);

        return [
            ...$result,
            'price' => $price,
        ];
    }
}
