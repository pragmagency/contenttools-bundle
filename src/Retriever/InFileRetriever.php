<?php

namespace Pragmagency\ContentTools\Retriever;

use Pragmagency\ContentTools\Configuration\ContenttoolsConfigurationInterface;
use Symfony\Component\Yaml\Yaml;

class InFileRetriever implements RetrieverInterface
{
    private $rootDir;
    private $configuration;

    private $loadedDomains = [];

    public function __construct(string $rootDir, ContenttoolsConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
        $this->rootDir = $rootDir;
    }

    public function retrieve(string $name, string $domain): string
    {
        $domainData = $this->getDomainData($domain);

        return $domainData[$name] ?? '';
    }

    private function getDomainData(string $domain): array
    {
        if (isset($this->loadedDomains[$domain])) {
            return $this->loadedDomains[$domain];
        }

        $relativeFilePath = $this->configuration->getConfig()['retriever']['files_path'];
        $absoluteFilePath = sprintf('%s/%s', $this->rootDir, $relativeFilePath);
        $domainPath = sprintf('%s/%s.yaml', $absoluteFilePath, $domain);

        if (!is_dir($absoluteFilePath)) {
            mkdir($absoluteFilePath, 0777, true);
        }

        if (!file_exists($domainPath)) {
            touch($domainPath);
        }

        $this->loadedDomains[$domain] = Yaml::parseFile($domainPath);

        return $this->loadedDomains[$domain] ?? [];
    }
}
