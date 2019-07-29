<?php

namespace Pragmagency\ContentTools\Configuration;

use Pragmagency\ContentTools\Persister\PersisterInterface;
use Pragmagency\ContentTools\Retriever\RetrieverInterface;
use Pragmagency\ContentTools\SecurityChecker\SecurityCheckerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

interface ContenttoolsConfigurationInterface
{
    public function getConfig(): array;

    /**
     * @param string $propertyPath
     *
     * @return mixed
     */
    public function get(string $propertyPath);

    public function setRetrieverLocator(ServiceLocator $retrieverLocator): void;

    public function setPersisterLocator(ServiceLocator $persisterLocator): void;

    public function setSecurityCheckerLocator(ServiceLocator $securityCheckerLocator): void;

    public function getPersister(): PersisterInterface;

    public function getRetriever(): RetrieverInterface;

    public function getSecurityChecker(): SecurityCheckerInterface;
}
