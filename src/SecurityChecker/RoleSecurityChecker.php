<?php

namespace Pragmagency\ContentTools\SecurityChecker;

use Pragmagency\ContentTools\Configuration\ContenttoolsConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class RoleSecurityChecker implements SecurityCheckerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $configuration;

    public function __construct(ContenttoolsConfigurationInterface $configuration) {
        $this->configuration = $configuration;
    }

    public function check(array $options = []): bool
    {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        return $this->container->get('security.authorization_checker')->isGranted(
            $this->configuration->get('[security][role]')
        );
    }
}
