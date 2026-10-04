<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\LocaleLinks;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

/**
 * Fills the NAVBAR_LANGUAGE slot with a language dropdown whenever more than one locale is enabled
 * (kernel.enabled_locales) and survos_tabler.app.header.locale_switcher is on (the default). Apps used to copy the
 * whole navbar just to add a locale switcher; now the base template renders this slot and an app can add to it,
 * reorder it, or remove it with a listener of its own.
 */
final class LocaleMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        private readonly LocaleLinks $links,
        private readonly bool $enabled = true,
        protected readonly ?RouterInterface $router = null,
        protected readonly ?RouteAliasService $routeAliasService = null,
        protected readonly ?IconService $iconService = null,
    ) {}

    #[AsEventListener(event: MenuEvent::NAVBAR_LANGUAGE, priority: 100)]
    public function languages(MenuEvent $event): void
    {
        if (!$this->enabled || !$this->links->hasMultiple()) {
            return;
        }

        $parent = $this->addSubmenu(
            $event->menu,
            LocaleLinks::name($this->links->current()),
            icon: 'language',
            translationDomain: false,
        );
        foreach ($this->links->all() as $locale) {
            $item = $this->add(
                $parent,
                uri: $locale['url'],
                label: LocaleLinks::name($locale['code']),
                inferIcon: false,
                translationDomain: false,
                checkRouteExists: false,
            );
            $item->setExtra('locale', $locale['code']);
            $item->setLinkAttribute('hreflang', $locale['code']);
            if ($locale['current']) {
                $item->setCurrent(true);
            }
        }
    }
}
