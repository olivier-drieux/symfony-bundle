<?php

namespace App\LinkwebBundle\WebScrapingBundle\src\ApiResource;

use ApiPlatform\Metadata;
use ApiPlatform\OpenApi\Model;
use Linkweb\Annotation\ApiResource;
use Linkweb\Constant\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebScraping.
 */
#[ApiResource(
    self::class,
    paginationEnabled: false,
    operations: [
        new Metadata\Get(
            routeName: 'api_get_url_companyInfo',
            openapi: new Model\Operation(
                responses: [
                    Response::HTTP_OK => new Model\Response(),
                    Response::HTTP_BAD_REQUEST => new Model\Response(ResponseMessage::BAD_REQUEST),
                    Response::HTTP_UNAUTHORIZED => new Model\Response(ResponseMessage::UNAUTHORIZED),
                ],
                summary: 'Retrieves company information from the website at a given URL.',
                description: 'Retrieves company information from the website at a given URL.',
                parameters: [
                    new Model\Parameter(
                        name: 'url',
                        in: 'query',
                        description: 'The URL.',
                        required: true,
                        schema: [
                            'type' => 'string',
                            'example' => 'https://www.example.com',
                        ],
                    ),
                ],
            ),
        ),
    ]
)]
class WebScraping
{
    // ApiResources does not need any content
}
