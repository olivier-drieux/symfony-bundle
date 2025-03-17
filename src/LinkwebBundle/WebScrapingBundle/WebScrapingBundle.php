<?php

namespace App\LinkwebBundle\WebScrapingBundle;

use App\LinkwebBundle\Traits\AbstractBundleTrait;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
/**
 * @link https://symfony.com/doc/current/bundles/best_practices.html
 */
class WebScrapingBundle extends AbstractBundle
{
    use AbstractBundleTrait;

    /**
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $this->dependencyInjection($container);
    }
}