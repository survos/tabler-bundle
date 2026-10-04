<?php
/* src/Service/MenuDispatcher.php v2.1 - Root item not displayed */

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Survos\TablerBundle\Event\MenuEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MenuDispatcher
{
    /**
     * Built menus for the current request, by slot and resolved options. A layout asks "has items?" and then renders
     * the same slot, and the breadcrumb looks at other slots too: without this every listener ran two or more times
     * per slot per request. Keyed on the request object, so a worker never serves one request's menu to the next.
     *
     * @var \WeakMap<Request, array<string, ItemInterface>>
     */
    private \WeakMap $built;

    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly MenuOptionsResolver $menuOptionsResolver,
        private readonly ?RequestStack $requests = null,
    ) {
        $this->built = new \WeakMap();
    }

    public function dispatch(string $slot, array $options = []): ItemInterface
    {
        $options = $this->menuOptionsResolver->resolve($options);

        $request = $this->requests?->getCurrentRequest();
        $key = $slot.'|'.self::fingerprint($options);
        if ($request !== null && isset($this->built[$request][$key])) {
            return $this->built[$request][$key];
        }

        $menu = $this->build($slot, $options);
        if ($request !== null) {
            $memo = $this->built[$request] ?? [];
            $memo[$key] = $menu;
            $this->built[$request] = $memo;
        }

        return $menu;
    }

    /** Objects count by identity, so two different entities never share a menu. */
    private static function fingerprint(mixed $value): string
    {
        $normalise = static function (mixed $v) use (&$normalise) {
            return match (true) {
                is_object($v) => $v::class.'#'.spl_object_id($v),
                is_array($v) => array_map($normalise, $v),
                is_scalar($v) || $v === null => $v,
                default => get_debug_type($v),
            };
        };

        return md5(json_encode($normalise($value), JSON_PARTIAL_OUTPUT_ON_ERROR) ?: '');
    }

    private function build(string $slot, array $options): ItemInterface
    {
        // Create root menu - name doesn't matter since it won't be displayed
        $menu = $this->factory->createItem($options['name'] ?? $slot);

        // IMPORTANT: Don't display the root item itself, only its children
        $menu->setDisplay(false);

        $event = new MenuEvent($menu, $this->factory, $options);
        $this->dispatcher->dispatch($event, $slot);

        return $menu;
    }

    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }
}
