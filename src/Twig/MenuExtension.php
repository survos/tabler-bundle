<?php
/* src/Twig/MenuExtension.php v1.0 - Twig functions for menu rendering */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig;

use Knp\Menu\ItemInterface;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\MenuContext;
use Survos\TablerBundle\Service\MenuRenderer;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MenuExtension extends AbstractExtension
{
    public function __construct(
        private readonly MenuRenderer $renderer,
        private readonly MenuContext $menuContext,
        private readonly RequestStack $requestStack,
        private readonly bool $debugMenuSlotsEnabled = false,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tabler_menu', [$this, 'renderMenu'], ['is_safe' => ['html']]),
            new TwigFunction('tabler_menu_has_items', [$this, 'hasItems']),
            new TwigFunction('tabler_menu_options', [$this, 'setMenuOptions'], ['is_safe' => ['html']]),
            new TwigFunction('tabler_menu_context_summary', [$this, 'getMenuContextSummary']),
            new TwigFunction('tabler_menu_debug_enabled', [$this, 'isDebugEnabled']),
            new TwigFunction('tabler_menu_slot_states', [$this, 'getSlotStates']),
        ];
    }

    public function renderMenu(string $slot, array $options = []): string
    {
        return $this->renderer->render($slot, $options);
    }

    public function hasItems(string $slot, array $options = []): bool
    {
        return $this->renderer->hasItems($slot, $options);
    }

    public function setMenuOptions(array $options = [], mixed $caller = null): string
    {
        if ($caller !== null) {
            $options['caller'] = is_scalar($caller) ? (string) $caller : get_debug_type($caller);
        }

        $this->menuContext->addOptions($options);

        return '';
    }

    /**
     * @return array<string, string>
     */
    public function getMenuContextSummary(): array
    {
        $summary = [];

        foreach ($this->menuContext->getOptions() as $key => $value) {
            if (!is_string($key) || $value === null) {
                continue;
            }

            $formatted = $this->formatMenuOptionValue($value);
            if ($formatted === null) {
                continue;
            }

            $summary[$key] = $formatted;
        }

        return $summary;
    }

    public function isDebugEnabled(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return $this->debugMenuSlotsEnabled;
        }

        return $this->debugMenuSlotsEnabled || $request->query->getBoolean('debugMenuSlots');
    }

    /**
     * @return list<array{slot:string, area:string, hasItems:bool}>
     */
    public function getSlotStates(): array
    {
        $states = [];

        foreach (MenuEvent::getConstants() as $slot) {
            $states[] = [
                'slot' => $slot,
                'area' => self::BASE_LAYOUT_AREAS[$slot] ?? 'custom',
                'hasItems' => $this->hasItems($slot),
            ];
        }

        return $states;
    }

    private const BASE_LAYOUT_AREAS = [
        MenuEvent::BANNER => 'banner',
        MenuEvent::NAVBAR_START => 'top nav / left',
        MenuEvent::NAVBAR_THEME => 'top nav / right',
        MenuEvent::NAVBAR_NOTIFICATIONS => 'top nav / right',
        MenuEvent::NAVBAR_APPS => 'top nav / right',
        MenuEvent::NAVBAR_LANGUAGE => 'top nav / right',
        MenuEvent::NAVBAR_END => 'top nav / right',
        MenuEvent::SEARCH => 'top nav / right',
        MenuEvent::AUTH => 'top nav / right',
        MenuEvent::NAVBAR_MENU => 'secondary nav / main',
        MenuEvent::NAVBAR_MENU_END => 'secondary nav / right',
        MenuEvent::BREADCRUMB => 'page header / breadcrumb',
        MenuEvent::PAGE_ACTIONS => 'page header / actions',
        MenuEvent::PAGE_NAV => 'page header / tabs',
        MenuEvent::SIDEBAR => 'page body / sidebar',
        MenuEvent::FOOTER => 'footer / left',
        MenuEvent::FOOTER_END => 'footer / right',
    ];

    private function formatMenuOptionValue(mixed $value): ?string
    {
        if (is_scalar($value)) {
            return (string) $value;
        }

        if (is_object($value)) {
            $shortName = (new \ReflectionClass($value))->getShortName();
            $stringValue = $this->stringifyObject($value);

            return $stringValue !== null
                ? sprintf('%s: %s', $shortName, $stringValue)
                : $shortName;
        }

        if (is_array($value)) {
            return sprintf('array[%d]', count($value));
        }

        return null;
    }

    private function stringifyObject(object $value): ?string
    {
        if (!method_exists($value, '__toString')) {
            return null;
        }

        try {
            $stringValue = trim((string) $value);
        } catch (\Throwable) {
            return null;
        }

        return $stringValue !== '' ? $stringValue : null;
    }
}
