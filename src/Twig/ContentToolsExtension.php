<?php

namespace Pragmagency\ContentTools\Twig;

use Pragmagency\ContentTools\Configuration\ContenttoolsConfigurationInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ContentToolsExtension extends AbstractExtension
{
    private $environment;
    private $configuration;

    public function __construct(Environment $environment, ContenttoolsConfigurationInterface $configuration)
    {
        $this->environment = $environment;
        $this->configuration = $configuration;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('editable', [$this, 'editable'], ['is_safe' => ['html']]),
            new TwigFunction('editableScript', [$this, 'editableScript'], ['is_safe' => ['html']]),
        ];
    }

    public function editable(
        string $name,
        string $domain = 'messages',
        string $tagName = 'div',
        array $attributes = []
    ) {
        if ($this->isAllowedToEdit($domain)) {
            $attributes['data-editable'] = '';
            $attributes['data-name'] = sprintf('%s/%s', $domain, $name);
        }

        return sprintf(
            '<%s%s>%s</%s>',
            $tagName,
            $this->buildParametersString($attributes),
            $this->configuration->getRepository()->retrieve($name, $domain),
            $tagName
        );
    }

    private function buildParametersString(array $parameters): string
    {
        $string = '';

        foreach ($parameters as $name => $value) {
            $string .= sprintf(' %s="%s"', $name, $value);
        }

        return $string;
    }

    public function editableScript(): string
    {
        if (!$this->isAllowedToEdit()) {
            return '';
        }

        $scriptContent = $this->environment->render('script.html.twig');

        return $scriptContent;
    }

    private function isAllowedToEdit(string $domain = null): bool
    {
        return $this->configuration->getSecurityChecker($domain)->check();
    }
}
