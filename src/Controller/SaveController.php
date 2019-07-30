<?php

namespace Pragmagency\ContentTools\Controller;

use Pragmagency\ContentTools\Configuration\ContenttoolsConfigurationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route(path="/contenttools")
 */
class SaveController extends AbstractController
{
    private $configuration;

    public function __construct(ContenttoolsConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @Route("/save", name="contenttools_save", methods={"POST"})
     */
    public function save(Request $request)
    {
        if (!$this->configuration->getSecurityChecker()->check()) {
            throw new AccessDeniedException();
        }

        $requestContent = $request->getContent();

        if (
            empty($requestContent) ||
            null === ($decodedContent = json_decode($requestContent, true)) ||
            JSON_ERROR_NONE !== json_last_error()
        ) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $valuesByDomain = [];

        foreach ($decodedContent as $domainAndName => $value) {
            if (1 !== preg_match('~(.+)/(.+)~', $domainAndName, $matches)) {
                continue;
            }

            $valuesByDomain[$matches[1]][$matches[2]] = $value;
        }

        foreach ($valuesByDomain as $domain => $values) {
            if (!$this->configuration->getSecurityChecker($domain)->check()) {
                continue;
            }

            $this->configuration->getRepository($domain)->persist($values, $domain);
        }

        return new JsonResponse();
    }
}
