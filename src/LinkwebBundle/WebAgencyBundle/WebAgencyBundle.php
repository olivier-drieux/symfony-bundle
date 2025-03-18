<?php

namespace App\LinkwebBundle\WebAgencyBundle;

use App\LinkwebBundle\Utils\BundleHandler;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * @see https://symfony.com/doc/current/bundles/best_practices.html
 */
class WebAgencyBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        BundleHandler::setup(
            'WebScrapingBundle',
            $container,
            [
                'envs' => [],
                'bundles' => [DoctrineMigrationsBundle::class],
                'classes' => [],
            ]
        );
    }

    public function getContainerExtension(): ?WebAgencyExtension
    {
        return new WebAgencyExtension();
    }
}
