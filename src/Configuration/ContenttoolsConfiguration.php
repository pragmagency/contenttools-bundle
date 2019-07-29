<?php

namespace Pragmagency\ContentTools\Configuration;

use Pragmagency\ContentTools\Persister\PersisterInterface;
use Pragmagency\ContentTools\Retriever\RetrieverInterface;
use Pragmagency\ContentTools\SecurityChecker\SecurityCheckerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class ContenttoolsConfiguration implements ContenttoolsConfigurationInterface
{
    /** @var array */
    private $config;
    /** @var ServiceLocator */
    private $retrieverLocator;
    /** @var ServiceLocator */
    private $persisterLocator;
    /** @var ServiceLocator */
    private $securityCheckerLocator;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function setRetrieverLocator(ServiceLocator $retrieverLocator): void
    {
        $this->retrieverLocator = $retrieverLocator;
    }

    public function setPersisterLocator(ServiceLocator $persisterLocator): void
    {
        $this->persisterLocator = $persisterLocator;
    }

    public function setSecurityCheckerLocator(ServiceLocator $securityCheckerLocator): void
    {
        $this->securityCheckerLocator = $securityCheckerLocator;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function get(string $propertyPath)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        return $propertyAccessor->getValue($this->getConfig(), $propertyPath);
    }

    public function getPersister(): PersisterInterface
    {
        return $this->persisterLocator->get($this->get('[persistence][type]'));
    }

    public function getRetriever(): RetrieverInterface
    {
        return $this->retrieverLocator->get($this->get('[persistence][type]'));
    }

    public function getSecurityChecker(): SecurityCheckerInterface
    {
        return $this->securityCheckerLocator->get($this->get('[security_checker][type]'));
    }
}
