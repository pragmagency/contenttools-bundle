<?php

namespace Pragmagency\ContentTools\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('pa_contenttools');
            $rootNode = $treeBuilder->getRootNode();
        }

        $rootNode
            ->children()
            ->scalarNode('base_path')->defaultValue('bundles/contenttools/')->end()
            ->end();

        return $treeBuilder;
    }
}
