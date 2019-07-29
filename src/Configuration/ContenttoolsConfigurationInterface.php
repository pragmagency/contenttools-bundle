<?php

namespace Pragmagency\ContentTools\Configuration;

interface ContenttoolsConfigurationInterface
{
    public function getConfig(): array;

    /**
     * @param string $propertyPath
     *
     * @return mixed
     */
    public function get(string $propertyPath);
}
