<?php

namespace App\LinkwebBundle\WebScrapingBundle;

use App\LinkwebBundle\Traits\AbstractBundleTrait;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
    }
}