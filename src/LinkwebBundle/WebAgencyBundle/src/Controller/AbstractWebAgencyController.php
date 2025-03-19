<?php

namespace App\LinkwebBundle\WebAgencyBundle\src\Controller;

use App\Entity\WebAgency;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Linkweb\Constant\QueryFilter;
use Linkweb\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractWebAgencyController.
 */
abstract class AbstractWebAgencyController extends AbstractApiController
{
    #[Route(path: 'api/web_agencies', name: 'api_get_webAgency', methods: ['get'])]
    /**
     * @throws \Exception
     */
    public function getWebAgency(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        // Find all WebAgencies from requested query
        $webAgencies = $entityManager->getRepository(WebAgency::class)->extraFindBy($request->query->all(), [
            'name' => [QueryFilter::SORT => Order::Ascending],
        ]);

        $serializeData = $serializer->serialize($webAgencies, 'json', ['groups' => 'output']);

        return new JsonResponse($serializeData, json: true);
    }
}
