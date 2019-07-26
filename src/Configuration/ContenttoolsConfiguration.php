<?php

namespace Pragmagency\ContentTools\Configuration;

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
}
