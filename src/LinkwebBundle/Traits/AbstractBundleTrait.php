<?php

namespace App\LinkwebBundle\Traits;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
trait AbstractBundleTrait
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function dependencyInjection(ContainerBuilder $container): void
    {
        $packagesLoader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config/packages'));
        $configLoader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config'));

        // Try to load doctrine_migrations.yaml
        try {
            $packagesLoader->load('doctrine_migrations.yaml');
        }catch (\Exception) {
            // Do nothing
        }

        // Try to load services.yaml
        try {
            $configLoader->load('services.yaml');
        }catch (\Exception) {
            // Do nothing
        }
    }
}