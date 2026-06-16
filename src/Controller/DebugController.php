<?php
/* src/Controller/DebugController.php v1.0 - Debug pages for menu slots */

declare(strict_types=1);

namespace Survos\TablerBundle\Controller;

use Survos\TablerBundle\Service\MenuContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DebugController extends AbstractController
{
    public function __construct(
        private readonly MenuContext $menuContext,
    ) {}

    /**
     * Renders a page with every menu slot outlined and populated with dummy data.
     *
     * Setting the `dummy_menu` menu option turns on the slot outline (same as
     * ?debugMenuSlots) and signals {@see \Survos\TablerBundle\Menu\DebugMenu} to
     * fill each slot with representative items.
     */
    #[Route('/debug-menu', name: 'survos_tabler_debug_menu')]
    public function debugMenu(): Response
    {
        $this->menuContext->addOptions(['dummy_menu' => true]);

        return $this->render('@SurvosTabler/page/debug_menu.html.twig');
    }
}
