<?php

namespace App\LinkwebBundle\WebAgencyBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
/**
 * @link https://symfony.com/doc/current/bundles/best_practices.html
 */
class WebAgencyBundle extends AbstractBundle
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config/packages'));
        $loader->load('doctrine_migrations.yaml');
    }
}