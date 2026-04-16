<?php
/* src/Twig/MenuExtension.php v1.0 - Twig functions for menu rendering */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig;

use Knp\Menu\ItemInterface;
use Survos\TablerBundle\Service\MenuContext;
use Survos\TablerBundle\Service\MenuRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MenuExtension extends AbstractExtension
{
    public function __construct(
        private readonly MenuRenderer $renderer,
        private readonly MenuContext $menuContext,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tabler_menu', [$this, 'renderMenu'], ['is_safe' => ['html']]),
            new TwigFunction('tabler_menu_has_items', [$this, 'hasItems']),
            new TwigFunction('tabler_menu_options', [$this, 'setMenuOptions'], ['is_safe' => ['html']]),
            new TwigFunction('tabler_menu_context_summary', [$this, 'getMenuContextSummary']),
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
