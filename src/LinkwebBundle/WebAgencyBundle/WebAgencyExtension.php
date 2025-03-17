<?php

namespace App\LinkwebBundle\WebAgencyBundle;

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
        // Fetch existing doctrine_migrations configuration
        $doctrineConfigs = $container->getExtensionConfig('doctrine_migrations');

        // Default migration paths from this bundle
        $bundleMigrations = [
            'App\LinkwebBundle\WebAgencyBundle\src\Migrations'=> '%kernel.project_dir%/src/LinkwebBundle/WebAgencyBundle/src/Migrations'
        ];

        // Initialize the migrations paths
        $mergedMigrationsPaths = [];

        // Check if there is an existing doctrine_migrations configuration
        foreach ($doctrineConfigs as $config) {
            if (isset($config['migrations_paths']) && \is_array($config['migrations_paths'])) {
                $mergedMigrationsPaths = \array_merge($config['migrations_paths'], $bundleMigrations);
            }
        }

        // If no existing configuration was found, use only the bundle migrations
        if (empty($mergedMigrationsPaths)) {
            $mergedMigrationsPaths = $bundleMigrations;
        }

        // Prepend the merged configuration
        $container->prependExtensionConfig('doctrine_migrations', [
            'migrations_paths' => $mergedMigrationsPaths,
        ]);
    }
}
