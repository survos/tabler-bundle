<?php
/* src/Controller/FaviconController.php v1.0 - serves the dynamic SVG favicon */

declare(strict_types=1);

namespace Survos\TablerBundle\Controller;

use Survos\TablerBundle\Service\FaviconService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FaviconController extends AbstractController
{
    public function __construct(
        private readonly FaviconService $faviconService,
    ) {
    }

    #[Route('/favicon.svg', name: 'survos_tabler_favicon', methods: ['GET'])]
    public function favicon(): Response
    {
        if (!$this->faviconService->isEnabled()) {
            throw $this->createNotFoundException();
        }

        $response = new Response($this->faviconService->renderSvg(), Response::HTTP_OK, [
            'Content-Type' => 'image/svg+xml',
        ]);
        $response->setPublic();
        $response->setMaxAge(86400);
        $response->headers->addCacheControlDirective('immutable');

        return $response;
    }
}
