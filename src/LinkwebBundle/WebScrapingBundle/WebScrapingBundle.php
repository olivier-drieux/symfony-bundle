<?php

namespace App\LinkwebBundle\WebScrapingBundle;

use App\Entity\WebAgency;
use App\LinkwebBundle\Utils\BundleHandler;
use App\LinkwebBundle\WebAgencyBundle\WebAgencyBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * @see https://symfony.com/doc/current/bundles/best_practices.html
 */
class WebScrapingBundle extends AbstractBundle
{
    /**
     * @throws \Exception
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        BundleHandler::setup(
            'WebScrapingBundle',
            $container,
            [
                'envs' => ['PROXY_COMMON', 'PROXY_GOOGLE', 'PROXY_GOUV'],
                'bundles' => [WebAgencyBundle::class],
                'classes' => [WebAgency::class],
            ]
        );
    }
}
