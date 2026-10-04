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

## Behaviour on an item: attributes, dropdowns, search boxes

Items are not just links. Every slot template carries the item's attributes through, so a listener can express what apps
used to get by overriding a whole template block:

```php
// a link that opens a Stimulus-driven dialog without navigating
$this->add($menu, uri: '#', label: 'footer.cookie_settings', checkRouteExists: false)
    ->setLinkAttribute('data-action', 'click->klaro#open:prevent');

// a dropdown whose container and entries carry data-* for your JS
$picker = $this->addSubmenu($event->getMenu(), 'Fortepan', icon: 'tabler:palette', translationDomain: false);
$picker->setAttribute('data-theme-switcher', true);                       // on the wrapper (<li> / .nav-item)
$this->add($picker, uri: '#', label: 'Airtable', checkRouteExists: false)
    ->setLinkAttribute('data-theme-key', 'airtable');                     // on the <a>

// a search box instead of a button: uri = where it submits (GET), label = the placeholder
$this->add($event->getMenu(), 'tenant_gallery', $tenant, label: 'Search...', translationDomain: false)
    ->setExtra('form', true);                                             // ->setExtra('name', 'q') is the default field
```

`true` prints a bare attribute, `false`/`null` print nothing. A parent with children in `NAVBAR_THEME`, `NAVBAR_APPS`,
`NAVBAR_NOTIFICATIONS`, `NAVBAR_START` or `NAVBAR_END` renders as a dropdown. Apps no longer need to override
`navbar`, `navbar_admin` or `footer` to add a link, a switcher or a search box; do that in a listener.

## The locale switcher

When more than one locale is in `kernel.enabled_locales` (and `survos_tabler.app.header.locale_switcher` is on, the
default), `LocaleMenuSubscriber` fills `NAVBAR_LANGUAGE` with a language dropdown, so every app gets one with no template
work. Add to it, reorder it or remove it with a listener on `NAVBAR_LANGUAGE`. For a switcher somewhere else (a footer, a
landing page) use `<twig:tabler:locale-switcher />`; both use `LocaleLinks`, which handles `/{_locale}/...` routes and
`fr.example.org` subdomains.

## Debugging

`?debugMenuSlots=1` (or the "Slots" toggle in the admin navbar) outlines and labels every slot rendered on the
page. `/debug-menu` lists them all.

## Removed

* `KnpMenuEvent` and its constants (use `MenuEvent`).
* `component('tabler:menu', {type: SLOT, caller: _self})` for page slots (use `tabler_menu(SLOT)`).
* The unused `menu/tabler_*.html.twig` template set and `MenuRenderer`'s template map.
* `render_sidebar_menu.html.twig` / `render_page_menu.html.twig`.
