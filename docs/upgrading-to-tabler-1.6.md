# Upgrading an app to Tabler 1.6

Tabler 1.5 moved Bootstrap into Tabler's own source, and 1.6 turned five plugins into real components and made the CSS
lighter. **You do not need to change your markup.** What does need cleaning up is the JavaScript wiring: an app that
still loads the standalone `bootstrap` package next to `@tabler/core` loads Bootstrap twice or, worse, loses the
data-api and its dropdowns stop opening.

## The cleanup

1. Pin `@tabler/core` and `@tabler/core/dist/css/tabler.min.css` to `1.6.1` in `importmap.php`. `composer update
   survos/tabler-bundle` does this for you (Flex reads this bundle's `assets/package.json`), except for entries pinned by
   `path` (a vendor-patched copy of `tabler.esm.js`): replace that with `['version' => '1.6.1']` and delete
   `assets/vendor-patched/@tabler`. It is no longer needed.
2. Remove the `bootstrap`, `@popperjs/core` and `bootstrap/dist/css/bootstrap.min.css` pins.
3. In your JS, replace `import 'bootstrap'` / `import * as bootstrap from 'bootstrap'` / `import { Offcanvas } from
   'bootstrap'` with the same import from `'@tabler/core'`, and `import 'bootstrap/dist/css/bootstrap.min.css'` with
   `import '@tabler/core/dist/css/tabler.min.css'`. `import '@tabler/core'` alone registers the data-api.
4. Replace `{{ component('tabler:menu', {type: SLOT, caller: _self}) }}` and `<twig:tabler:menu :type="SLOT">` with
   `{{ tabler_menu(SLOT) }}` (context goes in the second argument). The old forms now throw.
5. Run `php bin/console importmap:install`, and **delete `public/assets`** if it exists. A compiled `public/assets/manifest.json`
   makes AssetMapper ignore your importmap and JS edits in dev.
6. Click a dropdown.

## Tools (in the mono repository)

```bash
bin/tabler-audit   ~/sites          # which apps still need what (core pin, bootstrap pins/imports, old menu calls, ...)
bin/tabler-migrate ~/sites/myapp    # dry run; add --apply to make steps 1-4 (it skips files with uncommitted changes)
bin/tabler-smoke   myapp            # boot the app and check Tabler is wired
```

## Things that bite

* **Bootstrap-era pins left behind** show up as `Unable to find asset "bootstrap/dist/css/bootstrap.min.css"` when a
  secondary entry (for example `meili.js` or `admin.js`) still imports it.
* **A missing `marked` pin** used to abort the whole JS entry (`Failed to resolve module specifier "marked"`) and with it
  every dropdown. `js-twig-bundle` now treats it as optional and shows a notice; this bundle declares it so Flex installs it.
* **Popper is still in Tabler.** 1.6 bundles it inside its own JS (no separate pin needed); what you removed is only the
  duplicate standalone copy.
