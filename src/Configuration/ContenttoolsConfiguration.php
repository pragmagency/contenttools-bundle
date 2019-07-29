<?php

namespace Pragmagency\ContentTools\Configuration;

use Symfony\Component\PropertyAccess\PropertyAccess;

final class ContenttoolsConfiguration implements ContenttoolsConfigurationInterface
{
    /** @var array */
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
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
}
