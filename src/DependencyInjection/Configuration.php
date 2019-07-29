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
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')->defaultValue('file')->end()
                        ->scalarNode('files_path')->defaultValue('translations/contenttools')->end()
                    ->end()
                ->end()
                ->arrayNode('security_checker')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')->defaultValue('role')->end()
                        ->scalarNode('role')->defaultValue('ROLE_EDIT')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
