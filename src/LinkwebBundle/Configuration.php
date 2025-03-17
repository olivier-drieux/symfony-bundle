<?php

namespace App\LinkwebBundle;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('your_bundle');

        $treeBuilder->getRootNode()
            ->children()
            ->arrayNode('migrations_paths')
            ->useAttributeAsKey('namespace')
            ->scalarPrototype()->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
