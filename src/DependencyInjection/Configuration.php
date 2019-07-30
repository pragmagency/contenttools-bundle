<?php

namespace Pragmagency\ContentTools\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
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

        $main = $rootNode->children();
        $main = $this->addConfigChild($main);
        $main = $this->addDomainsChild($main);
        $main->end();

        return $treeBuilder;
    }

    private function addConfigChild(NodeBuilder $builder, bool $setDefault = false)
    {
        $persistenceNode = $builder
            ->arrayNode('persistence');

        if ($setDefault) {
            $persistenceNode = $persistenceNode->addDefaultsIfNotSet();
        }

        $persistenceNode = $persistenceNode
                ->children()
                    ->scalarNode('type')->defaultValue('file')->end()
                    ->scalarNode('files_path')->defaultValue('translations/contenttools')->end()
                ->end()
            ->end();

        $securityNode = $persistenceNode
            ->arrayNode('security');

        if ($setDefault) {
            $securityNode = $securityNode->addDefaultsIfNotSet();
        }

        return $securityNode
                ->children()
                    ->scalarNode('type')->defaultValue('role')->end()
                    ->scalarNode('role')->defaultValue('ROLE_EDIT')->end()
                ->end()
            ->end()
        ;
    }

    private function addDomainsChild(NodeBuilder $builder)
    {
        $domainsChildren = $builder
            ->arrayNode('domains')
                ->arrayPrototype()
                    ->children();

        $domainsChildren = $this->addConfigChild($domainsChildren, false);

        return $domainsChildren
                    ->end()
                ->end()
            ->end()
        ;
    }
}
