<?php

namespace App\LinkwebBundle\WebScrapingBundle\src\Service;

use App\Entity\WebAgency;
use Doctrine\ORM\EntityManagerInterface;
use Linkweb\Exception\ApiException;
use Psr\Log\LoggerInterface;

/**
 * Class WebScrapingService.
 */
readonly class WebScrapingService
{
    /**
     * WebScrapingService constructor.
     */
    public function __construct(private EntityManagerInterface $entityManager) {
        // Nothing to do here
    }

    /**
     * @return array{siret: string|null, siren: string|null, webAgency: WebAgency|null}
     *
     * @throws ApiException
     */
    public function searchCompanyInfoFromUrl(string $url): array
    {
        /** @var WebAgency[] $webAgencies */
        $webAgencies = $this->entityManager->getRepository(WebAgency::class)->findAll();

        return ['siret' => null, 'siren' => null, 'webAgency' => null];
    }
}
