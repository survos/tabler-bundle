<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Components;

use Survos\TablerBundle\Service\MenuSlotRegistry;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

/**
 * <twig:tabler:menu name="card-actions" :options="{card: card}" /> : a menu that belongs to an element,
 * not to a slot of the page shell. The name is just an event name; listeners populate it.
 *
 * It is only sugar over tabler_menu(). The registered page slots (NAVBAR_END, SIDEBAR, ...) are rendered
 * with {{ tabler_menu(SLOT) }} and are refused here on purpose: the component used to render them too,
 * which left two render paths and a stateful service in the middle of the page shell.
 */
#[AsTwigComponent('tabler:menu', template: '@SurvosTabler/components/menu.html.twig')]
final class MenuComponent
{
    /** the event name that listeners populate */
    public string $name = '';

    /** context for the listeners (what $event->getOption() returns) */
    public array $options = [];

    /** KnpMenu render overrides, e.g. {template: '@App/menu/card.html.twig'} */
    public array $render = [];

    /** removed: this was the slot name; use {{ tabler_menu(SLOT) }} */
    public ?string $type = null;

    public function __construct(private readonly MenuSlotRegistry $slots) {}

    /** PostMount, not mount(): UX sets the public props after mount() runs, so only here are they populated. */
    #[PostMount]
    public function validate(): void
    {
        $name = $this->name !== '' ? $this->name : (string) $this->type;

        if ($this->type !== null && $this->name === '') {
            throw new \LogicException(sprintf(
                'The "type" prop of <twig:tabler:menu> was removed. Render the page slot with {{ tabler_menu(%s) }}; '
                . 'use name="..." only for a menu that is not a slot (see docs/menus.md).',
                $this->type,
            ));
        }
        if ($name === '') {
            throw new \LogicException('<twig:tabler:menu> needs a name="...": the event name your listeners populate.');
        }
        if ($this->slots->has($name)) {
            throw new \LogicException(sprintf(
                '"%s" is a page slot. Render it with {{ tabler_menu(%s) }}, not the tabler:menu component '
                . '(the component is for menus attached to an element; see docs/menus.md).',
                $name,
                $name,
            ));
        }
        $this->name = $name;
    }
}
