# Menus

Every menu is built by PHP listeners and drawn by one function. Security and context decisions stay in PHP;
the templates carry no conditions.

## Populate a slot

A slot is an event name. Listen to it, add items to `$event->menu`:

```php
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Menu\MenuBuilderTrait;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ProjectMenu
{
    use MenuBuilderTrait;

    #[AsEventListener(event: MenuEvent::PAGE_NAV)]
    public function nav(MenuEvent $event): void
    {
        $project = $event->getOption('project');
        if (!$project instanceof Project) {
            return;                       // context-specific: only on pages that have a project
        }
        $this->add($event->menu, 'project_show', ['id' => $project->getId()], label: 'Overview');
    }
}
```

`add()` skips the item when the route's `#[IsGranted]` denies it, so menus are security-aware for free.

## Render a slot

```twig
{{ tabler_menu(NAVBAR_END) }}
{{ tabler_menu(PAGE_NAV, {project: project}) }}          {# 2nd arg: context for the listeners #}
{{ tabler_menu(SIDEBAR, {}, {rootAttributes: {class: 'navbar-nav'}}) }}   {# 3rd arg: render overrides #}
{% if tabler_menu_has_items(FOOTER) %}...{% endif %}
```

`NAVBAR_END`, `SIDEBAR`, `FOOTER`, ... are Twig globals (the `MenuEvent` constants). How each registered slot
is drawn (template, classes) lives in `MenuSlotRegistry`.

## A menu that is not a page slot

Any other name is a custom menu: nothing in the page shell owns it, so use it for a menu attached to an element.

```twig
{{ tabler_menu('card.actions', {card: card}) }}
<twig:tabler:menu name="card.actions" :options="{card: card}" />   {# same thing #}
```

Listeners attach with `#[AsEventListener(event: 'card.actions')]`. The `tabler:menu` component is only sugar over
`tabler_menu()`; it **refuses** registered page slots and the old `type:` prop with an exception, so a leftover
`component('tabler:menu', {type: NAVBAR_END})` fails loudly instead of rendering through a second path.

## Debugging

`?debugMenuSlots=1` (or the "Slots" toggle in the admin navbar) outlines and labels every slot rendered on the
page. `/debug-menu` lists them all.

## Removed

* `KnpMenuEvent` and its constants (use `MenuEvent`).
* `component('tabler:menu', {type: SLOT, caller: _self})` for page slots (use `tabler_menu(SLOT)`).
* The unused `menu/tabler_*.html.twig` template set and `MenuRenderer`'s template map.
* `render_sidebar_menu.html.twig` / `render_page_menu.html.twig`.
