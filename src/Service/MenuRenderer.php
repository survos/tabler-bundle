<?php
/* src/Service/MenuRenderer.php v1.2 - Renders menus for different slots */

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Knp\Menu\ItemInterface;
use Knp\Menu\Twig\Helper;
use Survos\TablerBundle\Event\MenuEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuRenderer
{
    private const TEMPLATE_MAP = [
        MenuEvent::BANNER => 'tabler_banner.html.twig',
        MenuEvent::NAVBAR_END => 'tabler_navbar.html.twig',
        MenuEvent::NAVBAR_MENU => 'tabler_navbar.html.twig',
        MenuEvent::NAVBAR_MENU_END => 'tabler_navbar.html.twig',
        MenuEvent::SIDEBAR => 'tabler_sidebar.html.twig',
        MenuEvent::PAGE_NAV => 'tabler_actions.html.twig',
        MenuEvent::PAGE_ACTIONS => 'tabler_actions.html.twig',
        MenuEvent::BREADCRUMB => 'tabler_breadcrumb.html.twig',
        MenuEvent::FOOTER => 'tabler_footer.html.twig',
        MenuEvent::FOOTER_END => 'tabler_footer.html.twig',
        MenuEvent::AUTH => 'tabler_auth.html.twig',
        MenuEvent::SEARCH => 'tabler_search.html.twig',
    ];

    public function __construct(
        private readonly MenuDispatcher $dispatcher,
        private readonly Helper $knpHelper,
        private readonly RequestStack $requestStack,
        private readonly string $templatePrefix = '@SurvosTabler/menu/',
    ) {}

    public function render(string $slot, array $options = []): string
    {
        $menu = $this->dispatcher->dispatch($slot, $options);

        if (!$menu->hasChildren()) {
            return '';
        }

        $template = $this->templatePrefix . (self::TEMPLATE_MAP[$slot] ?? 'tabler_navbar.html.twig');

        return $this->knpHelper->render($menu, [
            'template' => $template,
            'allow_safe_labels' => true,
            'currentClass' => 'active',
        ]);
    }

    public function getMenu(string $slot, array $options = []): ItemInterface
    {
        return $this->dispatcher->dispatch($slot, $options);
    }

    public function hasItems(string $slot, array $options = []): bool
    {
        return $this->dispatcher->dispatch($slot, $options)->hasChildren();
    }
}
