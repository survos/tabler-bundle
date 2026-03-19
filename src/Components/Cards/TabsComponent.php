<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Reusable tab card component.
 *
 * Usage:
 *   <twig:cards:tabs id="my-tabs" :tabs="[
 *       {id: 'overview', label: 'Overview'},
 *       {id: 'details',  label: 'Details', icon: 'list'},
 *       {id: 'tasks',    label: 'Tasks',   badge: '3'},
 *   ]" activeTab="overview" hashNavigation=true>
 *       {% block tab_overview %}...{% endblock %}
 *       {% block tab_details %}...{% endblock %}
 *       {% block tab_tasks %}...{% endblock %}
 *   </twig:cards:tabs>
 *
 * Props:
 *   id              Unique HTML id prefix (required to support multiple tab sets per page)
 *   tabs            Array of {id, label, icon?, badge?}
 *   activeTab       Tab id to show on first render (default: first tab)
 *   hashNavigation  If true, reads/writes location.hash for bookmarkable tabs
 *   animation       If true, adds Bootstrap fade animation
 *   justified       If true, tabs fill the full width (nav-fill)
 *   reverse         If true, tabs are right-aligned
 */
#[AsTwigComponent(name: 'cards:tabs', template: '@SurvosTabler/components/cards/tabs.html.twig')]
final class TabsComponent
{
    use DataAwareTrait;

    /** Unique id prefix — required when multiple tab sets appear on one page */
    public string $id = 'tabs';

    /** Tab definitions: [{id: string, label: string, icon?: string, badge?: string}] */
    public array $tabs = [];

    /** Which tab is active on first render. Defaults to first tab. */
    public ?string $activeTab = null;

    /** Sync active tab with location.hash for bookmarkable/shareable URLs */
    public bool $hashNavigation = false;

    public bool $animation = false;
    public bool $justified = false;
    public bool $reverse = false;

    // Legacy fixture props (kept so demo pages don't break)
    public ?string $hideText = null;
    public ?string $icons = null;
    public ?string $activity = null;
    public ?bool $disabled = null;
    public ?string $dropdown = null;
    public ?string $settings = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }

    public function getActiveTab(): string
    {
        if ($this->activeTab !== null) {
            return $this->activeTab;
        }
        if (!empty($this->tabs)) {
            return (string) ($this->tabs[0]['id'] ?? '');
        }
        return '';
    }
}
