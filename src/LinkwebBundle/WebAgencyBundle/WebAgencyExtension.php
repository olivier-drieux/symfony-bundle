<?php

namespace App\LinkwebBundle\WebAgencyBundle;

use App\LinkwebBundle\Utils\BundleHandler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * @link https://symfony.com/doc/current/bundles/best_practices.html
 */
class WebAgencyExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Standard service loading (if needed)
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Instead of YAML, use array objects to configure the bundle
        $configs = [
            'doctrine_migrations' => [
                'migrations_paths' => [
                    'App\LinkwebBundle\WebAgencyBundle\src\Migrations' => '%kernel.project_dir%/src/LinkwebBundle/WebAgencyBundle/src/Migrations',
                ],
            ],
        ];

        BundleHandler::mergeConfig($container, $configs);
    }
}
