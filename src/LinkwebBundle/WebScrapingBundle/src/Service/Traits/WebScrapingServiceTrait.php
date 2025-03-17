<?php

namespace App\LinkwebBundle\WebScrapingBundle\src\Service\Traits;

use App\Constant\ValidationRegex;
use App\Entity\Cms;
use App\Entity\WebAgency;
use App\LinkwebBundle\Constant\Proxy;
use App\Model\SearchCompanyFromUrlModel;
use App\Utils\DataSanitizer;
use Linkweb\Constant\Device;
use Linkweb\Exception\ApiException;
use Linkweb\Utils\Pinger;
use Linkweb\Utils\StringHandler;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

/**
 * Trait WebScrapingServiceTrait.
 */
trait WebScrapingServiceTrait
{
    /**
     * @param string      $url
     * @param string      $device   default set to Device::DESKTOP
     * @param string|null $debugKey default set to null
     * @param array       $options  default set to []
     *
     * @return array{html: string, price: float}
     *
     * @throws ApiException
     */
    public function getUrlHtmlContent(string $url, string $device = Device::DESKTOP, string|null $debugKey = null, array $options = []): array
    {
        // Initialization of variables used for the process
        $htmlContent = null;
        $attempts = 1;
        $price = 0;
        // Check if the URL is a Google one
        $isGoogleUrl = 1 === \preg_match("/^https?:\/\/(?:[a-zA-Z]+\.)?google\./", $url);
        // Check if the URL is a government one
        $isGouvUrl = 1 === \preg_match("/\b(?:https?:\/\/)?(?:[a-zA-Z0-9\-]+\.)?gouv\.fr\b(?:\/[\S]*)?/", $url);

        // Ping to validate the reachability of all URLs other than Google ones
        if (false === $isGoogleUrl && false === Pinger::ping($url)) {
            throw new ApiException(
                'webscraping.url.invalid',
                Response::HTTP_NOT_FOUND,
                translationParameters: ['%url%' => $url]
            );
        }

        // Loop HTML content retrieved or maximum number of attempts reached
        while (null === $htmlContent && $attempts <= Proxy::PROXY_MAX_ATTEMPTS) {
            try {
                if ($isGoogleUrl) {
                    $htmlContent = $this->webScrapingWebService->googleRequest($url, $device, $options)->getContent();
                } elseif ($isGouvUrl) {
                    $htmlContent = $this->webScrapingWebService->gouvRequest($url, $device, $options)->getContent();
                } else {
                    $htmlContent = $this->webScrapingWebService->commonRequest($url, $device, $options)->getContent();
                }
            } catch (ExceptionInterface) {
                // Nothing to do, exception is already logged by the web service
            }

            // Calculate the potential request
            if (null !== $htmlContent) {
                if ($isGoogleUrl) {
                    $price += Proxy::PROXY_GOOGLE_PRICE_PER_BITE * \mb_strlen($htmlContent);
                } elseif ($isGouvUrl) {
                    $price += Proxy::PROXY_GOUV_PRICE_PER_BITE * \mb_strlen($htmlContent);
                } else {
                    $price += Proxy::PROXY_COMMON_PRICE_PER_BITE * \mb_strlen($htmlContent);
                }
            }

            // Increment the number of attempts
            ++$attempts;
        }

        // If the maximum number of attempts has been made without success
        if (null === $htmlContent || $attempts > Proxy::PROXY_MAX_ATTEMPTS) {
            // Throw a 400 bad request error exception
            throw new ApiException(
                'webscraping.request.max_attempts',
                translationParameters: ['%url%' => $url, '%attempts%' => Proxy::PROXY_MAX_ATTEMPTS]
            );
        }

        // Create a debug file if asked
        if (null !== $debugKey) {
            \file_put_contents("debug/{$debugKey}_webscraping_$device.html", $htmlContent);
        }

        return [
            'html' => \mb_convert_encoding($htmlContent, 'UTF-8', 'auto'),
            'price' => $price,
        ];
    }

    /**
     * @param string $url
     *
     * @return array{siret: string|null, siren: string|null, webAgency: WebAgency|null}
     *
     * @throws ApiException
     */
    public function searchCompanyInfoFromUrl(string $url): array
    {
        /** @var WebAgency[] $webAgencies */
        $webAgencies = $this->entityManager->getRepository(WebAgency::class)->findAll();

        /** @var Cms[] $cmses */
        $cmses = $this->entityManager->getRepository(Cms::class)->findAll();

        // Initialize info model
        $searchCompanyFromUrlInfo = (new SearchCompanyFromUrlModel())->setBaseUrl($url);

        // Try to retrieve webAgency from URL
        $this->searchWebAgencyFromUrl($searchCompanyFromUrlInfo, $webAgencies);

        // Get HTML content from URL
        ['html' => $html] = $this->getUrlHtmlContent($url);

        // Try to retrieve info from index
        $this->searchInfoFromUrl(
            $searchCompanyFromUrlInfo,
            $html,
            $webAgencies,
            $cmses,
            searchSiretAndSiren: false,
            searchPhones: false
        );

        // Create crawler from non accentuated lowercase HTML
        $crawler = new Crawler(\mb_strtolower(StringHandler::nonAccentuated($html)), $url);

        // Search for links pointing to legal mentions
        $links = $crawler->filterXPath($this->getLegalLinksxPath());

        try {
            // If there is at least one link found
            ['html' => $html] = 0 < \count($links) ?
                // Get HTML content behind the first link
                $this->getUrlHtmlContent($links->first()->link()->getUri())
                // Else, attempt to scrape legal mentions page by directly accessing the legal mentions url
                : $this->getUrlHtmlContent(\rtrim($url, '/').'/mentions-legales');

            // At this point a legal mentions page has been found, search information in it
            $this->searchInfoFromUrl(
                $searchCompanyFromUrlInfo,
                $html,
                $webAgencies,
                $cmses,
                searchAddresses: false,
                searchPhones: false
            );
        } catch (ExceptionInterface) {
            // Silent catch since the legal mention page might not exist and throw a 404 error
        }

        try {
            // If there is at least one link found
            ['html' => $html] = $this->getUrlHtmlContent(\rtrim($url, '/').'/contact');

            // At this point a contact page has been found, search information in it
            $this->searchInfoFromUrl(
                $searchCompanyFromUrlInfo,
                $html,
                $webAgencies,
                $cmses,
                searchSiretAndSiren: false,
                searchWebAgency: false
            );
        } catch (ExceptionInterface) {
            // Silent catch since the legal mention page might not exist and throw a 404 error
        }

        return $searchCompanyFromUrlInfo->getDataAsArray();
    }

    /**
     * Retrieve xPath with all the legal mentions words in website links.
     *
     * @return string
     */
    private function getLegalLinksxPath(): string
    {
        // Prepare legal mentions words
        $legalWords = [
            'mentions legales',
            'mentions légales',
            'mentions-légales',
            'mentions-legales',
            'informations legales',
            'informations légales',
            'informations-legales',
            'informations-légales',
            'mentions',
            'mention',
            'terms',
            'legal',
        ];
        $xPath = '//a[';
        foreach ($legalWords as $index => $word) {
            if (0 !== $index) {
                $xPath .= ' or ';
            }
            $xPath .= "contains(., '$word') or contains(@href, '$word') or starts-with(., '$word') or starts-with(@href, '$word')";
        }
        $xPath .= ']';

        return $xPath;
    }

    /**
     * @param string      $url
     * @param string      $device   default set to Device::DESKTOP
     * @param string|null $debugKey default set to null
     * @param array       $options  default set to []
     *
     * @return array{crawler: Crawler, price: float}
     *
     * @throws ApiException
     */
    protected function getUrlHtmlCrawler(string $url, string $device = Device::DESKTOP, string|null $debugKey = null, array $options = []): array
    {
        // Retrieve HTML content of URL
        ['html' => $html, 'price' => $price] = $this->getUrlHtmlContent($url, $device, $debugKey, $options);

        return [
            'crawler' => new Crawler($html),
            'price' => $price,
        ];
    }

    /**
     * @param string      $search
     * @param string      $device     default set to Device::DESKTOP
     * @param string|null $location   default set to null
     * @param string|null $debugKey   default set to null
     * @param array       $extraQuery default set to []
     *
     * @return array{crawler: Crawler, price: float}
     *
     * @throws ApiException
     */
    protected function getGoogleSearchHtmlCrawler(string $search, string $device = Device::DESKTOP, string|null $location = null, string|null $debugKey = null, array $extraQuery = []): array
    {
        // Similar to getUrlHtmlCrawler() but with pre-defined query for Google search
        return $this->getUrlHtmlCrawler('https://www.google.fr/search', $device, $debugKey, [
            'query' => \array_merge([
                ...$extraQuery,
                'q' => $search,
                // Set the language to french
                'hl' => 'fr',
                // Set country to France
                'gl' => 'fr',
                // Disable personalization
                'pws' => '0',
                'uule' => $this->calculateUule($location),
            ], $extraQuery),
        ]);
    }

    /**
     * @param string|null $location
     *
     * @return string|null
     *
     * @see https://moz.com/blog/geolocation-the-ultimate-tip-to-emulate-local-search
     */
    protected function calculateUule(string|null $location): string|null
    {
        if (null === $location) {
            return null;
        }

        // Define the secrets and the length of the location
        $secrets = '    EFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789- ABCDEFGHIJMTL';
        $locationLength = \mb_strlen($location);

        // No UULE for locations longer than the secrets
        if ($locationLength > \mb_strlen($secrets)) {
            return null;
        }

        // Get the secret for the location length
        $secret = $secrets[$locationLength];
        // Base64 encode the location
        $base64Location = \base64_encode($location);

        // Construct the UULE parameter
        return 'w+CAIQICI'.$secret.$base64Location;
    }

    /**
     * @param string  $url
     * @param Crawler $crawler
     * @param bool    $isExactMatch
     * @param bool    $withSerp
     *
     * @return array{positions: array{ position: int, url: string, title: string }[], serp: array{ position: int, url: string, title: string }[]}
     */
    protected function parseGoogleResultForUrlKeywordPositions(string $url, Crawler $crawler, bool $isExactMatch, bool $withSerp): array
    {
        // Select all the results from the Google search
        $googleResults = $crawler->filterXPath('//a[@class="fuLhoc ZWRArf"]');

        // Remove trailing slash from the URL
        $url = \rtrim($url, '/');

        $positions = [];
        $serp = [];

        // Loop through the results
        $googleResults->each(
            function (Crawler $node, int $position) use ($isExactMatch, $withSerp, $url, &$positions, &$serp) {
                // Retrieve the content of the current node
                $currentSerp = $this->getGoogleSearchNodeContent($node, $position);
                // Check if the SERP URL is an exact match or contains the URL
                if ((null !== $currentSerp['url'] && ($isExactMatch && $url === \rtrim($currentSerp['url'], '/'))) || (!$isExactMatch && \str_contains($currentSerp['url'], $url))) {
                    // Save the matched position
                    $positions[] = $currentSerp;
                }

                // Only save the first 50 results
                if ($withSerp && 50 > $position) {
                    $serp[] = $currentSerp;
                }
            }
        );

        return [
            'positions' => $positions,
            'serp' => $serp,
        ];
    }

    /**
     * @param Crawler $node
     * @param int     $position
     *
     * @return array{ position: int, url: string|null, title: string }
     */
    protected function getGoogleSearchNodeContent(Crawler $node, int $position): array
    {
        $urlNode = $node->attr('href');
        \preg_match('/\/url\?q=(.+)&sa=U/', $urlNode, $match);

        /**
         * Retrieve the span node containing at least "CVA68e qXLe6d" classes.
         *
         * @see https://devhints.io/xpath#class-check
         */
        $titleNode = $node->filterXPath('//span[contains(concat(" ",normalize-space(@class)," "),"CVA68e qXLe6d")]')->text();

        return [
            'position' => $position + 1,
            'url' => $match[1] ?? null,
            'title' => $titleNode,
        ];
    }

    /**
     * @param SearchCompanyFromUrlModel $searchCompanyFromUrlInfo
     * @param string                    $html
     * @param WebAgency[]               $webAgencies
     * @param array                     $cmses
     * @param bool                      $searchAddresses          default set to true
     * @param bool                      $searchSiretAndSiren      default set to true
     * @param bool                      $searchWebAgency          default set to true
     * @param bool                      $searchPhones             default set to true
     */
    private function searchInfoFromUrl(
        SearchCompanyFromUrlModel $searchCompanyFromUrlInfo,
        string $html,
        array $webAgencies,
        array $cmses,
        bool $searchAddresses = true,
        bool $searchSiretAndSiren = true,
        bool $searchWebAgency = true,
        bool $searchPhones = true,
    ): void {
        // Search emails from HTML
        $this->searchEmailsFromHtml($searchCompanyFromUrlInfo, $html);
        // Search CMS from HTML
        $this->searchCmsFromHtml($searchCompanyFromUrlInfo, $html, $cmses);

        // If searchSiretAndSiren allowed
        if ($searchSiretAndSiren) {
            $this->searchSiretAndSirenFromHtml($searchCompanyFromUrlInfo, $html);
        }

        // If searchPhones allowed
        if ($searchPhones) {
            $this->searchPhonesFromHtml($searchCompanyFromUrlInfo, $html);
        }

        // If searchAddresses allowed
        if ($searchAddresses) {
            $this->searchAddressFromHtml($searchCompanyFromUrlInfo, $html);
        }

        // If searchWebAgency allowed
        if ($searchWebAgency) {
            $this->searchWebAgencyFromHtml($searchCompanyFromUrlInfo, $html, $webAgencies);
        }
    }

    /**
     * @param SearchCompanyFromUrlModel $searchCompanyFromUrlModel
     * @param WebAgency[]               $webAgencies
     */
    private function searchWebAgencyFromUrl(SearchCompanyFromUrlModel $searchCompanyFromUrlModel, array $webAgencies): void
    {
        // If already found a WebAgency
        if (null !== $searchCompanyFromUrlModel->getWebAgency()) {
            return;
        }

        // Lowercase the URL to ease comparisons
        $url = \mb_strtolower($searchCompanyFromUrlModel->getBaseUrl());

        // Loop on each WebAgency
        foreach ($webAgencies as $webAgency) {
            // Attempt to find substring from the URL in the WebAgency with the given URL
            foreach ($webAgency->getUrlKeywords() as $webAgencyUrl) {
                // If the URL contains the WebAgency URL
                if (\str_contains($url, \mb_strtolower($webAgencyUrl))) {
                    // Set the WebAgency to the SearchCompanyFromUrlModel
                    $searchCompanyFromUrlModel->setWebAgency($webAgency);

                    return;
                }
            }
        }
    }

    /**
     * @param SearchCompanyFromUrlModel $searchCompanyFromUrlInfo
     * @param string                    $html
     * @param Cms[]                     $cmses
     */
    private function searchCmsFromHtml(SearchCompanyFromUrlModel $searchCompanyFromUrlInfo, string $html, array $cmses): void
    {
        // If already found a CMS
        if (null !== $searchCompanyFromUrlInfo->getCms()) {
            return;
        }

        // Loop on each CMS
        foreach ($cmses as $cms) {
            // Attempt to find any CMS 'html names' inside the given HTML with regex
            $regex = '/('.\implode('|', \array_map(static fn ($str) => \preg_quote($str, '/'), $cms->getKeywords())).')/iu';

            // If regex matching
            if (\preg_match($regex, $html)) {
                // Set the CMS to the SearchCompanyFromUrlModel
                $searchCompanyFromUrlInfo->setCms($cms);

                return;
            }
        }
    }

    /**
     * @param SearchCompanyFromUrlModel $searchCompanyFromUrlInfo
     * @param string                    $html
     * @param WebAgency[]               $webAgencies
     */
    private function searchWebAgencyFromHtml(SearchCompanyFromUrlModel $searchCompanyFromUrlInfo, string $html, array $webAgencies): void
    {
        // If already found a WebAgency
        if (null !== $searchCompanyFromUrlInfo->getWebAgency()) {
            return;
        }

        // Loop on each WebAgency
        foreach ($webAgencies as $webAgency) {
            // Attempt to find any WebAgency 'html names' inside the given HTML with regex
            $regexWebAgency = '/('.\implode('|', \array_map(static fn ($str) => \preg_quote($str, '/'), $webAgency->getHtmlNames())).')/iu';

            // If no regex matching
            if (!\preg_match($regexWebAgency, $html)) {
                // Skip to next WebAgency
                continue;
            }

            // Set the WebAgency to the SearchCompanyFromUrlModel
            $searchCompanyFromUrlInfo->setWebAgency($webAgency);

            // If no parked page keywords
            if (0 === \count($webAgency->getParkedPageKeywords())) {
                return;
            }

            // Attempt to find any parked page keywords inside the given HTML with regex
            $regexParkedPage = '/('.\implode('|', \array_map(static fn ($str) => \preg_quote($str, '/'), $webAgency->getParkedPageKeywords())).')/iu';

            // Set the parked page status to the SearchCompanyFromUrlModel
            $searchCompanyFromUrlInfo->setIsParkedPage(0 < \preg_match($regexParkedPage, $html));

            return;
        }
    }

    /**
     * @param SearchCompanyFromUrlModel $searchCompanyFromUrlModel
     * @param string                    $html
     */
    private function searchSiretAndSirenFromHtml(SearchCompanyFromUrlModel $searchCompanyFromUrlModel, string $html): void
    {
        // Initialize array to return
        $results = [
            'siret' => null,
            'siren' => null,
        ];

        // Create crawler from HTML in lowercase
        $crawler = new Crawler(\mb_strtolower($html));

        // Find nodes that contain specific terms
        $nodes = $crawler->filterXPath('//text()[
            contains(., "siret")
            or contains(., "sarl")
            or contains(., "siren")
            or contains(., "immatricul")
            or contains(., "societe")
        ]/../../..');

        // If no nodes found
        if (0 === \count($nodes)) {
            return;
        }

        foreach ($nodes as $node) {
            // Attempt to find SIRET
            \preg_match('/\d{3}\s*\d{3}\s*\d{3}\s*\d{5}/', $node->textContent, $matches);

            // If SIRET found
            if (0 < \count($matches)) {
                $results['siret'] = \array_unique([
                    ...($results['siret'] ?? []),
                    ...\preg_replace('/\D/', '', $matches),
                ]);
            }

            // Attempt to find SIREN in urlContent
            \preg_match('/\b\d{3}\s*\d{3}\s*\d{3}\b/iu', $node->textContent, $matches);

            // If SIREN found
            if (0 < \count($matches)) {
                $results['siren'] = \array_unique([
                    ...($results['siren'] ?? []),
                    ...\preg_replace('/\D/', '', $matches),
                ]);
            }
        }

        // Set the SIREN and SIRET to the SearchCompanyFromUrlModel
        $searchCompanyFromUrlModel
            ->setSiren($results['siren'][0] ?? null)
            ->setSiret($results['siret'][0] ?? null);
    }

    /**
     * @param SearchCompanyFromUrlModel $searchCompanyFromUrlModel
     * @param string                    $html
     */
    private function searchEmailsFromHtml(SearchCompanyFromUrlModel $searchCompanyFromUrlModel, string $html): void
    {
        // Initialize array to return
        $emails = [];

        \preg_match_all(ValidationRegex::EMAIL, $html, $emails);

        // Get unique emails and re-index the array
        $emails = \array_values(\array_unique($emails[0]));

        $searchCompanyFromUrlModel->setEmails($emails);
    }

    /**
     * @param SearchCompanyFromUrlModel $searchCompanyFromUrlModel
     * @param string                    $html
     */
    private function searchPhonesFromHtml(SearchCompanyFromUrlModel $searchCompanyFromUrlModel, string $html): void
    {
        // TODO Replace by api engine utils later
        // Initialize array to return
        $phones = [];

        \preg_match_all(ValidationRegex::PHONE, $html, $phones);

        // Get unique phones and re-index the array
        $phones = \array_values(\array_unique($phones[0]));

        // Format the phone numbers
        foreach ($phones as $key => $phone) {
            $phones[$key] = \str_replace('(0)', '', \preg_replace('/[-*.\s]/', '', $phone));
        }

        // Split phones by types
        ['homePhones' => $homePhones, 'mobilePhones' => $mobilePhones] = DataSanitizer::splitPhonesByTypes($phones);

        $searchCompanyFromUrlModel->setHomePhones($homePhones);
        $searchCompanyFromUrlModel->setMobilePhones($mobilePhones);
    }

    /**
     * @param SearchCompanyFromUrlModel $searchCompanyFromUrlModel
     * @param string                    $html
     */
    private function searchAddressFromHtml(SearchCompanyFromUrlModel $searchCompanyFromUrlModel, string $html): void
    {
        // TODO Replace by api engine utils later
        // Initialize array to return
        $addresses = [];

        \preg_match_all(ValidationRegex::SCRAP_ADDRESS, $html, $addresses);

        // TODO replace by ArrayHandler::unique() later
        // Get unique phones and re-index the array
        $addresses = \array_values(\array_unique($addresses[0]));

        $searchCompanyFromUrlModel->setAddresses($addresses);
    }
}
