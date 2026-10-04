# One menu builder API

`Survos\TablerBundle\Menu\MenuBuilderTrait` is the canonical API for new menus.
`Traits\KnpMenuHelperTrait` and `Traits\KnpMenuHelperInterface` are deprecated.
They remain available for compatibility; do not replace their imports mechanically.

## Route permissions

The bundle collects controller and method `#[IsGranted]` attributes into
`MenuService`. The canonical builder receives that service through its required
`setTablerMenuService()` setter when Symfony autowires the menu. Its `add()` checks
permission before creating a child, including when `checkRouteExists: false` is used
and when `addAliased()` delegates to it. Entity route parameters are passed to voters
as the original entity, before flattening them to route parameters.

A manually constructed menu builder must call `setTablerMenuService($service)`.
Controller authorization remains responsible for enforcing access; menu filtering
only controls navigation. External URLs have no route attributes to inspect.

## Migration checklist

| Legacy API | Canonical API |
| --- | --- |
| `add()` returns the builder, unless `returnItem: true` | Returns the created `ItemInterface`, or the parent if skipped |
| Fluent `$this->add(...)->add(...)` | Separate `$this->add(...)` calls |
| `returnItem: true` | Remove the argument |
| `dividerPrepend`, `dividerAppend` | `dividerBefore`, `dividerAfter` |
| `rp: null` | Omit it or pass `[]` |
| Default translation domain `routing` | Default `messages`; pass `routing` explicitly if needed |
| `dataAttributes` | Set link attributes on the created item, only if it differs from the parent |
| `baseUrl`, `translationParams`, `addMenuItem`, auth/workflow helpers | Review individually; no direct replacement promised |
| Legacy helper interface | Remove only after checking code that type-hints or autoconfigures it |

Prefer named arguments: positional argument order differs. Canonical helper methods
are protected; expose them explicitly only if external callers require it.

Autowire the menu listener and use the trait: its `#[Required]` setters receive
`MenuService` and the optional router, `IconService`, and `RouteAliasService`.
No base class or per-listener constructor boilerplate is needed. Existing
constructor-promoted helper properties remain supported and take precedence.
When constructing a listener manually, call `setTablerMenuHelpers()` with the
helpers it needs. Without a router, route links are skipped; URI links still work.
Without the icon service, explicit icon identifiers pass through unchanged.
Use `#[AsEventListener]` with `MenuEvent` to populate the appropriate slot.
Keep `addSubmenu()` and `addHeading()` for non-link items; a normal leaf needs a route
or URI. Verify the rendered menu for anonymous users, ordinary users and admins,
plus any entity voter paths used by the app.

## Validation

From the monorepo root:

```sh
vendor/bin/phpunit bu/tabler-bundle/tests/Menu/MenuBuilderAuthorizationTest.php
```

The tests cover denied/admin routes, public and external links, all required
permissions, and preservation of entity voter subjects. Ink provides the first live
consumer verification using `../mono/link .` with its application adapter removed.

## Local consumer audit

A source-only survey under `~/sites` (excluding vendor, cache and node_modules)
found roughly 45 legacy references, including alternate checkouts and stale imports.
Examples requiring deliberate migration:

- `mono/src/EventListener/AppMenuEventListener.php`: implements the legacy interface
  and uses `addMenuItem()` option arrays.
- `pressia/src/Menu/AppMenu.php`: uses option arrays and nested-menu return behavior.
- `kpa/src/Menu/AppMenu.php`: already uses the canonical trait but has a stale legacy import.
- `feeds` and maker generator templates still reference the older Bootstrap namespace;
  deprecating Tabler's trait does not migrate those separate APIs.

Modern first-party bundle menus already use `MenuBuilderTrait`, either directly
(such as Folio's `RowMenu`) or through `AbstractAdminMenuSubscriber`. That base class
is a consumer of the canonical trait, not a competing implementation. Retain the
legacy trait until app callers and generator output have been migrated and tested.
