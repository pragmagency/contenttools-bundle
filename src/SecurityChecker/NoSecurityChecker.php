<?php

namespace Pragmagency\ContentTools\SecurityChecker;

final class NoSecurityChecker implements SecurityCheckerInterface
{
    public function check(array $options = []): bool
    {
        return true;
    }
}
