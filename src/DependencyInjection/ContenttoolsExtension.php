<?php

namespace Pragmagency\ContentTools\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class ContenttoolsExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container): void
    {
        $this->loadResources($container);

        $container->getDefinition('pa_contenttools.configuration')->setArgument(0, $config);
    }

    private function loadResources(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $resources = ['configuration', 'installer'];

        foreach ($resources as $resource) {
            $loader->load($resource.'.yaml');
        }
    }
    public function getAlias(): string
    {
        return 'pa_contenttools';
    }
}
