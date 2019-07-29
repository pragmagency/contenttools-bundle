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

    public function getDomainData(string $domain): array
    {
        if (isset($this->loadedDomains[$domain])) {
            return $this->loadedDomains[$domain];
        }

        $absoluteFilePath = $this->getFilesPath();
        $domainPath = $this->getDomainFilePath($domain);

        if (!is_dir($absoluteFilePath)) {
            mkdir($absoluteFilePath, 0777, true);
        }

        if (!file_exists($domainPath)) {
            touch($domainPath);
        }

        $this->loadedDomains[$domain] = Yaml::parseFile($domainPath);

        return $this->loadedDomains[$domain] ?? [];
    }

    public function getFilesPath(): string
    {
        $relativeFilePath = $this->configuration->getConfig()['persistence']['files_path'];
        return sprintf('%s/%s', $this->rootDir, $relativeFilePath);
    }

    public function getDomainFilePath(string $domain): string
    {
        return sprintf('%s/%s.yaml', $this->getFilesPath(), $domain);
    }
}
