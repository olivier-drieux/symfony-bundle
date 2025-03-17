<?php

namespace App\LinkwebBundle\WebAgencyBundle;

use App\LinkwebBundle\Traits\AbstractBundleTrait;
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

        // Ensure DoctrineMigrationsBundle is loaded inside the bundle
        if (!class_exists(DoctrineMigrationsBundle::class)) {
            throw new \LogicException('DoctrineMigrationsBundle is not installed in the bundle.');
        }

        // Ensure the extension is being executed
        dump('WebAgencyBundle build() is executing...');
    }

    public function getContainerExtension(): WebAgencyExtension|null
    {
        return new WebAgencyExtension();
    }
}