<?php

namespace Pragmagency\ContentTools\Configuration;

use Pragmagency\ContentTools\Repository\RepositoryInterface;
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

    public function setRepositoryLocator(ServiceLocator $repositoryLocator): void;

    public function setSecurityCheckerLocator(ServiceLocator $securityCheckerLocator): void;

    public function getRepository(string $domain = null): RepositoryInterface;

    public function getSecurityChecker(string $domain = null): SecurityCheckerInterface;
}
