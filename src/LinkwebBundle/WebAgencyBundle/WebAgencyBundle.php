<?php

namespace App\LinkwebBundle\WebAgencyBundle;

use App\Entity\WebAgency;
use App\LinkwebBundle\Traits\AbstractBundleTrait;
use App\LinkwebBundle\Utils\BundleHandler;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;

/**
 * @link https://symfony.com/doc/current/bundles/best_practices.html
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

    public function getContainerExtension(): WebAgencyExtension|null
    {
        return new WebAgencyExtension();
    }
}