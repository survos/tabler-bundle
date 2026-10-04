# One menu builder API

`Survos\TablerBundle\Menu\MenuBuilderTrait` is the canonical menu API.
`Traits\KnpMenuHelperTrait` and `Traits\KnpMenuHelperInterface` have been removed.
This is a breaking API change: migrate consumers before updating the bundle.
Do not replace their imports mechanically.

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
| `dataAttributes` | Preserve the attributes expected by your renderer (link attributes, or `data_attributes` extras for custom templates); only mutate the result if it differs from the parent |
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
vendor/bin/phpunit bu/tabler-bundle/tests/Menu bu/maker-bundle/tests/MenuSkeletonTest.php
```

The tests cover route authorization, entity voter subjects, optional helper injection,
existing constructor properties, disabled translation domains, and generated menu listeners.
Also compile the application container and check rendered menus with its actual routes,
translations, and templates.

## Local consumer audit

The primary application checkouts were migrated before removing the legacy Tabler API:
harvest, ssai, zm, pressia, repo, fotostory, kpa, ai-pipeline-demo, bench, cue, depot,
global-giving, mediary, packages, priceit, and tree-demo. Mono's application menu,
Brevo's subscriber, and the maker generator now use the canonical API as well.
Fotostory's integration test checks route labels and localized URLs in English,
French, and Hungarian.

The source and configuration audit found no remaining consumers of the removed
Tabler trait or interface in primary checkouts. Alternate worktrees and archived
applications were excluded and must be checked before updating their dependencies.
The separate Bootstrap API used by feeds and the FwBundle API used by fw7-demo and
priceit's phone menu are outside this removal.

First-party bundle menus use `MenuBuilderTrait`, either directly (such as Folio's
`RowMenu`) or through `AbstractAdminMenuSubscriber`. That base class is a consumer
of the canonical trait, not a competing implementation.
