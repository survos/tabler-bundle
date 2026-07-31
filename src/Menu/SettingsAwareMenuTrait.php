<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\SettingsBundle\Service\SettingsManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Opt-in mixin for menu subscribers that want a survos/settings-bundle setting to
 * control whether one of their items shows -- `use` this alongside
 * MenuBuilderTrait or KnpMenuHelperTrait and pass settingEnabled(...) as add()'s
 * `if:` argument, e.g. `$this->add($menu, ..., if: $this->settingEnabled('show_tour', true))`.
 *
 * survos/settings-bundle is an OPTIONAL dependency of tabler-bundle (see
 * composer.json suggest). #[Required] SETTER injection, not a constructor arg,
 * is the point: every menu class that wants this only adds `use
 * SettingsAwareMenuTrait;` -- no constructor changes, no repeating "inject
 * SettingsManagerInterface, wire it through my own __construct" in each one.
 * Autowiring calls the setter automatically; for an app that hasn't required
 * settings-bundle at all, there's no service to inject and $settingsManager
 * simply stays null (same as any other optional autowired dependency in this
 * codebase -- see RowMenu's docblock for the same pattern via constructor
 * injection instead).
 */
trait SettingsAwareMenuTrait
{
    protected ?SettingsManagerInterface $settingsManager = null;

    #[Required]
    public function setSettingsManager(?SettingsManagerInterface $settingsManager = null): void
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * True when the named boolean setting is enabled for the current scope,
     * falling back to $default when settings-bundle isn't installed or the
     * setting was never explicitly set for this scope.
     */
    protected function settingEnabled(string $name, bool $default = false): bool
    {
        return (bool) ($this->settingsManager?->get($name, $default) ?? $default);
    }
}
