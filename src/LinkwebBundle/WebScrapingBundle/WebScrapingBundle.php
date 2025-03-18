<?php

namespace App\LinkwebBundle\WebScrapingBundle;

use App\LinkwebBundle\Traits\AbstractBundleTrait;
use App\LinkwebBundle\Utils\BundleHandler;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\LinkwebBundle\WebAgencyBundle\WebAgencyBundle;
use App\Entity\WebAgency;
/**
 * @link https://symfony.com/doc/current/bundles/best_practices.html
 */
class WebScrapingBundle extends AbstractBundle
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        BundleHandler::setup(
            'WebScrapingBundle',
            $container,
            [
                'envs' => ['DATABASE_USER'],
                'bundles' => [WebAgencyBundle::class],
                'classes' => [WebAgency::class],
            ]
        );
    }
}