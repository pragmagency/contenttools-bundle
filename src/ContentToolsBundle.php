<?php

namespace Pragmagency\ContentTools;

use Pragmagency\ContentTools\DependencyInjection\ContenttoolsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ContentToolsBundle extends Bundle
{
    public function getContainerExtension(): ContenttoolsExtension
    {
        return new ContenttoolsExtension();
    }
}
