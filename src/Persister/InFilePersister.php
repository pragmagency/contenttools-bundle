<?php

namespace Pragmagency\ContentTools\Persister;

use Pragmagency\ContentTools\Retriever\InFileRetriever;
use Symfony\Component\Yaml\Yaml;

class InFilePersister implements PersisterInterface
{
    private $retriever;

    public function __construct(InFileRetriever $retriever)
    {
        $this->retriever = $retriever;
    }

    public function persist(string $name, string $domain, string $value)
    {
        $domainData = $this->retriever->getDomainData($domain);
        $domainData[$name] = $value;

        $this->saveDomain($domain, $domainData);
    }

    public function persistMultiple(array $values)
    {
        $valuesByDomain = [];

        foreach ($values as $domainAndName => $value) {
            if (1 !== preg_match('~(.+)/(.+)~', $domainAndName, $matches)) {
                continue;
            }

            $valuesByDomain[$matches[1]][$matches[2]] = $value;
        }

        foreach ($valuesByDomain as $domain => $values) {
            $domainData = $this->retriever->getDomainData($domain);

            foreach ($values as $name => $value) {
                $domainData[$name] = $value;
            }

            $this->saveDomain($domain, $domainData);
        }
    }

    private function saveDomain(string $domain, array $domainData)
    {
        file_put_contents($this->retriever->getDomainFilePath($domain), Yaml::dump($domainData));
    }
}
