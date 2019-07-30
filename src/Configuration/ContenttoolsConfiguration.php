<?php

namespace Pragmagency\ContentTools\Configuration;

use Pragmagency\ContentTools\Repository\RepositoryInterface;
use Pragmagency\ContentTools\SecurityChecker\SecurityCheckerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class ContenttoolsConfiguration implements ContenttoolsConfigurationInterface
{
    /** @var array */
    private $config;
    /** @var ServiceLocator */
    private $repositoryLocator;
    /** @var ServiceLocator */
    private $securityCheckerLocator;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function setRepositoryLocator(ServiceLocator $repositoryLocator): void
    {
        $this->repositoryLocator = $repositoryLocator;
    }

    public function setSecurityCheckerLocator(ServiceLocator $securityCheckerLocator): void
    {
        $this->securityCheckerLocator = $securityCheckerLocator;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function get(string $propertyPath, string $domain = null)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $config = $this->getConfig();

        if (
            null !== $domain &&
            $propertyAccessor->isReadable($config, $domainConfigPath = sprintf('[domains][%s]', $domain)) &&
            $propertyAccessor->isReadable($config, $domainPropertyPath = sprintf('%s%s', $domainConfigPath, $propertyPath)) &&
            null !== ($value = $propertyAccessor->getValue($config, $domainPropertyPath))
        ) {
            return $value;
        }

        return $propertyAccessor->getValue($config, $propertyPath);
    }

    public function getRepository(string $domain = null): RepositoryInterface
    {
        return $this->repositoryLocator->get($this->get('[persistence][type]', $domain));
    }

    public function getSecurityChecker(string $domain = null): SecurityCheckerInterface
    {
        return $this->securityCheckerLocator->get($this->get('[security][type]', $domain));
    }
}
