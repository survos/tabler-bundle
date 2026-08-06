# Migrating an app off survos/bootstrap-bundle to survos/tabler-bundle

Recipe for the PHP 8.5 / Symfony 8.1 / tabler-bundle upgrade, distilled from doing it on zm,
ssai, openfoto, and (the hard way, with every edge case) news/hub. The mechanical parts
(composer bump, `KnpMenuEvent` → `MenuEvent`, Font Awesome → `ux_icon()`) are quick. The
time sink is always a handful of app-specific landmines — this doc exists so the next app
doesn't have to rediscover them.

## 1. Dependency versions

```
"php": "^8.5",
"symfony/*": "^7.4||^8.0",           // let composer resolve the exact 8.1.x
"doctrine/doctrine-bundle": "^3.3",
"doctrine/orm": "^3.6",
"doctrine/dbal": "^3 || ^4",          // NOT ^4.5 -- doctrine:schema:update/migrations:diff
                                       // need DBAL's Schema::edit() API, only in 4.5+, which
                                       // isn't stable yet. Composer resolves 4.4.x today; live
                                       // with the schema-diff commands being broken for now.
"api-platform/symfony": "^4.3",       // replaces api-platform/core
"api-platform/doctrine-orm": "^4.3",
"symfony/ux-icons": "^3.4",
"symfony/ux-autocomplete": "^3.4",
"symfony/ux-turbo": "^3.4",
"symfony/ux-twig-component": "^3.4",
"symfony/stimulus-bundle": "^3.4",
"symfony/maker-bundle": "^1.67",
"survos/tabler-bundle": "^2.18",      // replaces survos/bootstrap-bundle
"survos/state-bundle": "^2.18",       // replaces survos/workflow-helper-bundle
```

Most other `survos/*` packages are on the same "2.x, versioned by last-touched date" line now
(`survos/auth-bundle`, `command-bundle`, `deployment-bundle`, `js-twig-bundle`,
`simple-datatables-bundle`, `tree-bundle`, `api-grid-bundle`, `bunny-bundle`...). Check each
one's actual latest tag on Packagist rather than guessing — `survos/core-bundle` in particular
has **no compatible release** (still requires symfony ^7.3) and should be dropped, not pinned
(see §4).

A few packages simply have **no Symfony-8-compatible release yet** — Packagist's
`repo.packagist.org/p2/<pkg>.json` `require.symfony/*` field tells you fast. Known casualties
as of 2026-08: `debril/rss-atom-bundle`, `knplabs/dictionary-bundle`, `tetranz/select2entity-bundle`,
`tacman/graby`, `petkopara/multi-search-bundle`. Remove from `composer.json`, comment out their
`bundles.php` entry with a note (don't delete the line — future-you needs to know it existed),
and disable/rename any `config/packages/*.yaml` that configures them (e.g. `foo.yaml` →
`foo.yaml.disabled`) since an orphaned config key for a bundle that isn't loaded is a hard
error, not a warning. If the disabled package's classes are only referenced via lazy `use`
imports in Controllers/Commands (never actually called), the app boots fine and only that one
feature breaks at the call site. If a disabled package's class is used as a **typed
constructor argument on an autowired service** (a Twig extension, anything under
`_defaults: autoconfigure: true`), the container fails to compile — drop the type hint (to
`mixed = null`) and guard the one call site instead.

Some of the survos/* bumps have their own **internal namespace or class renames** worth
grepping for before you start editing usages:
- `survos/api-grid-bundle` 1.x → 2.x: `Survos\ApiGrid\*` → `Survos\ApiGridBundle\*` (bundle
  class and everything under it — filters, services, all moved root namespace).
- `survos/workflow-helper-bundle` → `survos/state-bundle`: `Survos\WorkflowBundle\*` →
  `Survos\StateBundle\*` (same relative paths for everything **except**
  `Message\AsyncTransitionMessage` → `Message\TransitionMessage`, same constructor signature).
- `survos/auth-bundle`: `Survos\AuthBundle\Services\AuthService` (plural) →
  `Survos\AuthBundle\Service\AuthService` (singular).

## 2. The menu constant mapping (the actual hard part)

`survos/tabler-bundle`'s `MenuDispatcher` only ever dispatches
`Survos\TablerBundle\Event\MenuEvent`, under **that class's own constant strings**. A
same-named `Survos\TablerBundle\Event\KnpMenuEvent` class also ships (a BC shim carrying the
*old* bootstrap-bundle constant values), but nothing dispatches under those strings anymore.
Type-hinting a listener at the old class and swapping only the `use` statement compiles fine
and then **silently never fires** — the nav/sidebar/footer/auth slot just renders empty, no
error, because the string the framework dispatches under no longer matches what
`#[AsEventListener(event: ...)]` is listening for. `NAVBAR_MENU` is the one slot whose string
value didn't change, which makes it an easy false-negative during testing.

| Old (`KnpMenuEvent`, bootstrap-bundle) | New (`MenuEvent`, tabler-bundle) |
|---|---|
| `NAVBAR_MENU` | `NAVBAR_MENU` (unchanged) |
| `SIDEBAR_MENU_EVENT` / `SIDEBAR_MENU` | `SIDEBAR` |
| `FOOTER_MENU` | `FOOTER` |
| `AUTH_MENU` | `AUTH` |
| `PAGE_MENU` | `PAGE_NAV` (or `PAGE_ACTIONS` if the content is action buttons, not tabs) |

For every class implementing menu listeners:
1. `use Survos\BootstrapBundle\Event\KnpMenuEvent;` → `use Survos\TablerBundle\Event\MenuEvent;`
   (same for `Service\MenuService`, `Traits\KnpMenuHelperTrait`, `Traits\KnpMenuHelperInterface`
   — same class/method names, just root-namespace swap).
2. Swap every `KnpMenuEvent::*` constant per the table above, including inside
   `#[AsEventListener(event: ...)]` attributes and `getSubscribedEvents()` arrays.
3. `MenuEvent` doesn't expose `getOptions(): array` (only `KnpMenuEvent` did) — use
   `$event->getOption('key', $default)` or the public readonly `$event->options` property.
4. If a class `implements KnpMenuHelperInterface`, its `supports()` method signature is
   `supports(KnpMenuEvent|MenuEvent $event): bool` (a union type) — narrowing your override to
   just `MenuEvent` is a contravariance violation and fatals at container-compile time.
5. `MenuService::addAuthMenu()` **does not exist** in tabler-bundle's `MenuService` — the AUTH
   slot is populated automatically by `Survos\TablerBundle\Menu\AuthSlotMenuSubscriber` (falls
   back to `app_login`/`app_register` if unconfigured). Delete any app code that still tries to
   call it.
6. Any twig template calling `component('menu', {...})` needs to become
   `component('tabler:menu', {...})` — namespaced twig components now.

**Verify, don't assume.** After the rewrite, `bin/console debug:event-dispatcher NAVBAR_MENU`
(and `SIDEBAR`, `FOOTER`, `AUTH`, `PAGE_NAV`) should list every app listener you just touched.
An empty or short list means a constant didn't get remapped.

## 3. Workflow registration: check for auto-discovery before hand-rolling it

If the app uses `Survos\StateBundle\Attribute\Workflow`/`Place`/`Transition` (or the old
workflow-helper-bundle attributes before you renamed them), **do not** try to register them via
a `config/packages/*.php` file calling `ConfigureFromAttributesService::configureFramework()`.
Two independent problems:

- That service's signature takes `Symfony\Config\FrameworkConfig $framework` — Config Builders
  (the whole point of that typed-closure mechanism) were deprecated in Symfony 7.4 and
  **removed in 8.0**. Any `config/packages/*.php` file with a `FrameworkConfig`-typed closure
  parameter fatals with "Could not resolve argument" the moment Symfony tries to load it —
  including your own app's copy of this exact pattern, not just the bundle's.
- Even if you patch around that (e.g. rebuild the same logic against `ContainerBuilder` +
  `prependExtensionConfig()` instead), **state-bundle already auto-discovers workflow classes**
  via `StatePrependExtension::prepend()` → `AttributesWorkflowConfigBuilder::build()`, scanning
  `%kernel.project_dir%/src/Workflow` (configurable via `survos_state.workflow_paths`) for any
  class carrying `#[Workflow]`. Registering the same class again manually produces **duplicate
  transition definitions** — Symfony's `StateMachineValidator` throws "A transition from a
  place/state must have an unique name" for the *first* transition name that's duplicated,
  which is confusing because the actual bug is "this workflow got registered twice," not
  anything about that specific transition.

  The fix, once you hit this, is almost always to **delete** the manual registration file
  entirely and trust the auto-scan — not to write a replacement.

`AttributesWorkflowConfigBuilder`'s class finder is a naive single-line regex
(`/\b(class|interface|trait)\s+(\w+)/`) over the raw file text, run *before* PHP parses the
file — a comment like `// @todo: add the entity class to attach this to.` matches "class" +
"to" before the regex ever reaches the real `class ArticleWorkflow` declaration further down,
and the file gets silently skipped (the workflow just never registers, no error). If a
`#[Workflow]`-carrying class isn't showing up in the scan, grep its file for the word "class"
or "trait" appearing in a comment above the real declaration.

## 4. survos/core-bundle retirement → survos/field-bundle

`survos/core-bundle` has no Symfony-8-compatible release. Its `RouteParametersTrait` /
`RouteParametersInterface` (URL-identity helpers used by `getRp()`/menu-building/link
generation) moved to `survos/field-bundle`, with a genuine API change:

```php
// Before (core-bundle)
use Survos\CoreBundle\Entity\RouteParametersInterface;
use Survos\CoreBundle\Entity\RouteParametersTrait;

class Owner implements RouteParametersInterface
{
    use RouteParametersTrait;
    public const UNIQUE_PARAMETERS = ['ownerId' => 'code'];
}

// After (field-bundle)
use Survos\FieldBundle\Entity\RouteParametersInterface;
use Survos\FieldBundle\Entity\RouteIdentityTrait;
use Survos\FieldBundle\Attribute\RouteIdentity;

#[RouteIdentity(field: 'code', key: 'ownerId')]
class Owner implements RouteParametersInterface
{
    use RouteIdentityTrait;
}
```

The attribute is walked up the parent class chain, so a shared base entity can carry a single
default (`#[RouteIdentity(field: 'id')]`) and subclasses only need their own attribute when
they override the field/key (i.e. mirror whatever `UNIQUE_PARAMETERS` const they used to
declare — one const entry maps to one attribute 1:1). An entity with **no** const and no
attribute anywhere in its chain still works via `RouteIdentityResolver`'s legacy fallback
(`{lcShortName}Id => getId()`) — but only if the trait is `RouteIdentityTrait`; that fallback
recurses infinitely if you left the old `RouteParametersTrait` in place with no attribute, so
don't half-migrate a class.

`Survos\CoreBundle\Traits\QueryBuilderHelperTrait`/`Interface` → moved to
`Survos\FieldBundle\Repository\*` (same names, same namespace-root swap, no API change).
`Survos\CoreBundle\Traits\JsonResponseTrait` → `Survos\TablerBundle\Traits\JsonResponseTrait`.
`Survos\CoreBundle\Entity\SurvosCoreEntity` has no replacement — if grep turns up only a dead
`use` import (no actual usage in the file body), just delete the import; it's not needed.

## 5. Font Awesome → ux_icon()

The mechanical bulk of it: `<i class="fa[srbld]? fa-X"></i>` → `{{ ux_icon('tabler:X') }}`,
with `mdi:X` as a fallback when Tabler's icon set doesn't have a matching name. A script beats
doing this by hand across dozens of templates — see the approach below (regex-driven, with a
curated name map for the ~40 most common Font Awesome slugs and an automatic `mdi:` fallback
for the long tail). Verify every fallback actually resolves before committing:

```bash
curl -s "https://api.iconify.design/tabler.json?icons=eye,pencil,..." \
  | php -r '$d=json_decode(file_get_contents("php://stdin"),true); echo implode(",", $d["not_found"]??[]);'
```

Watch for icon markup that lives **inside a Twig string literal** (e.g.
`label|default('<i class="fas fa-eye"></i>')|raw`) — a naive regex will "fix" it into
`label|default('{{ ux_icon('tabler:eye') }}')|raw`, which is invalid nested Twig (a `{{ }}`
expression inside a string literal that's itself inside a `{{ }}` expression). `bin/console
lint:twig templates/` catches these immediately after the bulk replace; fix by hand (usually a
ternary — `label is not empty ? label|raw : ux_icon('tabler:eye')` — instead of `|default()`).

If `config/packages/ux_icons.yaml` has `on_demand: false` (icons must be pre-fetched, not
resolved live against the Iconify API in prod), run `bin/console ux:icons:lock` once after the
bulk replace — it scans the whole project for `ux_icon('...')` calls and imports+locks every
icon it finds in one pass, including ones inside `{# commented-out #}` template blocks (which
is harmless, just imports a few extra icons that never render).

## 6. @tabler/core asset pinning

Symfony AssetMapper's default remote-package resolution goes through jsDelivr's `+esm`
auto-bundler, which silently strips module code whose only purpose is a side effect with no
exported binding — `@tabler/core`'s Bootstrap data-api self-registration (the delegated click
listeners that make `data-bs-toggle="dropdown"` work with zero JS of your own) is exactly that
shape, so every dropdown/collapse/tab in the app goes silently inert. Fix: pin the raw ESM dist
file instead of trusting the default resolution.

```php
// importmap.php
'@tabler/core' => [
    'path' => './vendor-patched/@tabler/core/tabler.esm.js',
],
'@tabler/core/dist/css/tabler.min.css' => [
    'version' => '1.4.0',   // CSS entry stays version-resolved, only the JS needs pinning
    'type' => 'css',
],
```

Download the real file from npm's `module` field (currently `dist/js/tabler.esm.js`), **not**
the `+esm` URL — or just copy it from an app that's already done this (openfoto, zm, ssai all
have `assets/vendor-patched/@tabler/core/tabler.esm.js`, byte-identical since they're all
pinned to the same `@tabler/core` version).

Also remove the app's old `bootstrap`/`bootstrap/dist/css/bootstrap.min.css` importmap entries
and any `@survos/bootstrap-bundle` block in `assets/controllers.json` — Tabler's CSS/JS
supersedes them. `datatables.net-*-bs5` packages stay (Tabler's markup is Bootstrap5-compatible,
and the datatables bs5 skin doesn't conflict).

## 7. Other Symfony 7→8 breakage you'll hit regardless of tabler-bundle

These aren't tabler-specific but you'll hit them the moment you're actually on Symfony 8.1, so
they land in the same PR:

- **`Request::get()` removed entirely.** It used to check `attributes` → `query` → `request`
  (POST body) in that priority order as a convenience. Mechanical fix: replace
  `$request->get($k, $default)` with
  `($request->attributes->get($k, $default) ?? $request->query->get($k, $default) ?? $request->request->get($k, $default))`
  — preserves the exact original fallback behavior, safe to do with a script (PHP's
  `token_get_all()` handles this far more reliably than regex, since it won't get confused by
  nested parens/commas-in-strings in the arguments). Skip any receiver that's *already*
  `->attributes->get(...)` / `->query->get(...)` / `->request->get(...)` — those are already
  correct calls, not the removed method, and re-wrapping them is wrong.
  `#[MapQueryParameter]`/`#[MapRequestPayload]` controller argument attributes are the
  idiomatic Symfony 6.3+ replacement for *new* code, but retrofitting every call site to use
  them is a separate, much larger effort than the mechanical fallback-chain fix — do the
  mechanical fix first to unblock the migration, modernize incrementally later.
- Command classes: `configure()` must declare `: void` now (`AbstractType::configureOptions()`
  and `::buildForm()` too).
- `Symfony\Component\Security\Core\Authorization\Voter\Voter::voteOnAttribute()` gained a 4th
  parameter (`?Vote $vote = null`) — any custom `Voter` subclass needs its override signature
  updated or it's a fatal "declaration must be compatible" at class-load time.
- `doctrine.orm.enable_lazy_ghost_objects` config key renamed to `enable_native_lazy_objects`.
- Several bundle-shipped routing resources moved from `.xml` to `.php`
  (`@FrameworkBundle/Resources/config/routing/errors.xml` →  `errors.php`, same for
  WebProfilerBundle's `wdt.xml`/`profiler.xml`, FOSJsRoutingBundle's `routing-sf4.xml` →
  `routing.php`) — Symfony 8 dropped XML route loading from `symfony/routing` entirely, so an
  app-level `config/routes/*.yaml` still pointing at the old `.xml` path fails to load, not just
  deprecation-warns.
- Several survos bundles now **self-register their own routes** via a `HasConfigurableRoutes`
  compiler pass (auth-bundle, crawler-bundle, state-bundle) — delete any app-level
  `config/routes/survos_*.yaml` that manually imports `@SurvosXBundle/config/routes.yaml`; the
  file usually doesn't exist anymore and the import fails. `command-bundle` is the exception —
  it still needs an app-level attribute-routing import:
  ```yaml
  survos_command:
      prefix: /admin/commands
      type: attribute
      resource:
          path: '@SurvosCommandBundle/src/Controller/'
          namespace: Survos\CommandBundle\Controller
  ```
- `survos/api-grid-bundle` 2.x: `sortable_fields(class)` twig function is gone (columns derive
  sortability from `#[Field]` metadata now — just delete the `sortableFields:` key from
  `stimulus_controller()`/`<twig:api_grid>` calls). The `<twig:api_grid :apiRoute="...">` prop
  is gone too — delete it, the component derives the URL from `api_route(class)` automatically
  once `:class` is passed.

## Working order

1. Composer bump (§1) — expect several rounds of "package X caps a dependency below what
   package Y needs," resolve one conflict at a time rather than guessing the whole tree upfront.
2. Bundle swap: `config/bundles.php`, `config/packages/survos_tabler.yaml` (port over the old
   `survos_bootstrap.yaml` settings — see ssai's file for the current schema).
3. Menu PHP rewrite (§2) — verify with `debug:event-dispatcher` before moving on, this is the
   one silent-failure risk in the whole migration.
4. Asset pinning (§6).
5. Icon sweep (§5).
6. Boot the app (`bin/console debug:container`, then an actual HTTP request) and fix whatever
   comes up next — §3, §4, and §7 are all things that only surface once the container actually
   compiles and a real request executes; don't expect to find them by reading code.
