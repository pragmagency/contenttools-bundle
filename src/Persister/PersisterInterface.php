<?php

namespace Pragmagency\ContentTools\Persister;

interface PersisterInterface
{
    public function persist(string $name, string $domain, string $value);

    /**
     * @param array  $values ["domain/name" => "value", ...]
     * @param string $domain
     *
     * @return mixed
     */
    public function persistMultiple(array $values);
}
