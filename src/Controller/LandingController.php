<?php

/* src/Controller/LandingController.php v1.0 - Landing page controller */

declare(strict_types=1);

namespace Survos\TablerBundle\Controller;

use Survos\TablerBundle\Service\LandingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LandingController extends AbstractController
{
    public function __construct(
        private readonly LandingService $landingService,
    ) {
    }

    #[Route('/{_locale}/landing', name: 'survos_tabler_landing', priority: 100)]
    public function landing(Request $request): Response
    {
        $landing = $this->landingService->loadLandingData();

        return $this->render('@SurvosTabler/landing/page.html.twig', [
            'landing' => $landing,
            'sections' => $this->landingService->getSectionOrder(),
        ]);
    }
}
