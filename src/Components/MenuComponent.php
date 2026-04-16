<?php
/* src/Components/MenuComponent.php v1.1 - Menu component that dispatches events */

declare(strict_types=1);

namespace Survos\TablerBundle\Components;

use Knp\Menu\ItemInterface;
use Knp\Menu\Twig\Helper;
use Survos\TablerBundle\Service\MenuDispatcher;
use Survos\TablerBundle\Service\MenuOptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent('tabler:menu', template: '@SurvosTabler/components/menu.html.twig')]
class MenuComponent
{
    public string $type;
    public ?string $caller = null;
    public array $path = [];
    public array $options = [];

    #[ExposeInTemplate]
    public ItemInterface $menuItem;

    public function __construct(
        private readonly MenuOptionsResolver $menuOptionsResolver,
        private readonly Helper $helper,
        private readonly MenuDispatcher $menuDispatcher,
    ) {}

    public bool $kids { get => $this->menuItem->hasChildren();}

    public function mount(
        string $type,
        ?string $caller = null,
        array $path = [],
        array $options = [],
    ): void {
        $this->type = $type;
        $this->caller = $caller;
        $this->path = $path;

        $this->options = $this->menuOptionsResolver->resolve($options);
        $this->options['caller'] = $caller;
        $this->options['type'] = $type;

        // Use helper to navigate path (for breadcrumbs, submenus)
        $menu = $this->menuDispatcher->dispatch($type, $this->options);
        $this->menuItem = $this->helper->get($menu, $path, $this->options);
    }

    #[ExposeInTemplate]
    public function hasChildren(): bool
    {
        return $this->menuItem->hasChildren();
    }
}
