<?php

namespace App\LinkwebBundle\WebScrapingBundle\src\Controller;

use App\LinkwebBundle\WebScrapingBundle\src\Service\WebScrapingService;
use Linkweb\Controller\AbstractApiController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class AbstractWebScrapingController.
 */
abstract class AbstractWebScrapingController extends AbstractApiController
{
    public function __construct(private readonly LoggerInterface $logger, private readonly WebScrapingService $webScrapingService)
    {
        // Nothing to do here
    }

    #[Route('/scraper', name: 'custom_scraper_home')]
    public function index(): Response
    {
        $this->logger->info('CustomScraperController accessed from main app');

        return new Response('Hello from Main App (CustomScraperController)!');
    }

    #[Route(path: '/test', name: 'webScraping_test', methods: ['get'])]
    /**
     * @param string $url mapped by query
     *
     * @throws \Throwable
     */
    public function getUrlCompanyInfo(#[MapQueryParameter(filter: FILTER_VALIDATE_URL)] string $url): JsonResponse
    {
        // Scrape URL to search for company information
        $result = $this->webScrapingService->searchCompanyInfoFromUrl($url);

        return $this->jsonApiResponse($result);
    }
}
