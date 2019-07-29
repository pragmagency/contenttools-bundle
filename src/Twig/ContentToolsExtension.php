<?php

namespace Pragmagency\ContentTools\Twig;

use Pragmagency\ContentTools\Retriever\RetrieverInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ContentToolsExtension extends AbstractExtension
{
    /** @var RetrieverInterface */
    private $retriever;
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function setRetriever(RetrieverInterface $retriever): void
    {
        $this->retriever = $retriever;
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
        array $parameters = []
    ) {
        if ($this->isAllowedToEdit()) {
            $parameters['data-editable'] = '';
            $parameters['data-name'] = $name;
        }

        return sprintf(
            '<%s%s>%s</%s>',
            $tagName,
            $this->buildParametersString($parameters),
            $this->retriever->retrieve($name, $domain),
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

    private function isAllowedToEdit(): bool
    {
        return true; // @todo
    }
}
