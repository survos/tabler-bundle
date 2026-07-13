<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

/**
 * Links to the inspector.dev dashboard when inspector-apm/inspector-symfony is
 * installed (registration is guarded by class_exists() in
 * SurvosTablerBundle::loadExtension(), so this bundle stays a soft dependency).
 *
 * Links straight to this app's monitoring page when INSPECTOR_APP_ID is set,
 * otherwise falls back to the generic inspector.dev homepage.
 */
final class InspectorMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        protected readonly ?RouterInterface   $router            = null,
        protected readonly ?RouteAliasService $routeAliasService = null,
        protected readonly ?IconService       $iconService       = null,
    ) {}

    #[AsEventListener(event: MenuEvent::ADMIN_NAVBAR_MENU)]
    public function onAdminNavbarMenu(MenuEvent $event): void
    {
        $appId = $_ENV['INSPECTOR_APP_ID'] ?? getenv('INSPECTOR_APP_ID') ?: null;

        $url = $appId
            ? "https://app.inspector.dev/apps/{$appId}/monitoring"
            : 'https://app.inspector.dev/';

        $this->add($event->getMenu(), uri: $url, label: 'Inspector', icon: 'tabler:chart-line', external: true);
    }
}
