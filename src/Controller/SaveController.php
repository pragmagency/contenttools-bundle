<?php

namespace Pragmagency\ContentTools\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/contenttools")
 */
class SaveController extends AbstractController
{
    /**
     * @Route("/save", name="contenttools_save", methods={"POST"})
     */
    public function save(Request $request)
    {
        $requestContent = $request->getContent();

        if (
            empty($requestContent) ||
            null === ($decodedContent = json_decode($requestContent, true)) ||
            JSON_ERROR_NONE !== json_last_error()
        ) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        // @todo real save

        return new JsonResponse();
    }
}
