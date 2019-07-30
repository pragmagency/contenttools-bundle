<?php

namespace Pragmagency\ContentTools\Repository;

interface RepositoryInterface
{
    public function persist(array $values, string $domain);

    public function retrieve(string $name, string $domain): string;
}
