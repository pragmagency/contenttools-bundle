<?php

namespace Pragmagency\ContentTools\Retriever;

interface RetrieverInterface
{
    public function retrieve(string $name, string $domain): string;
}
