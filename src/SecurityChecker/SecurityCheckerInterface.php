<?php

namespace Pragmagency\ContentTools\SecurityChecker;

interface SecurityCheckerInterface
{
    public function check(array $options = []): bool;
}
