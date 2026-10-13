<?php

/* src/Components/Traits/DataAwareTrait.php v1.0 - Auto-load data for components */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Traits;

use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Trait for components that can load data from various sources.
 *
 * Usage in component:
 *   use DataAwareTrait;
 *
 * In template:
 *   <twig:cards:users-list fixture="people" :limit="10"/>
 *   <twig:cards:users-list endpoint="/api/users"/>
 *   <twig:cards:users-list :data="myData"/>
 */
trait DataAwareTrait
{
    // Injected services (component must declare these in constructor)
    protected ?FixtureService $fixtureService = null;
    protected ?HttpClientInterface $httpClient = null;

    // Direct data pass-through
    public ?iterable $data = null;

    // Load from bundle fixture
    public ?string $fixture = null;

    // Fetch from HTTP endpoint
    public ?string $endpoint = null;

    // Pagination
    public ?int $limit = null;
    public ?int $offset = null;

    // Cache for resolved items
    private ?array $resolvedItems = null;

    #[ExposeInTemplate]
    public function getItems(): iterable
    {
        if (null !== $this->resolvedItems) {
            return $this->resolvedItems;
        }

        $items = $this->resolveItems();

        // Apply limit/offset
        if (null !== $this->limit || null !== $this->offset) {
            $items = array_slice(
                is_array($items) ? $items : iterator_to_array($items),
                $this->offset ?? 0,
                $this->limit
            );
        }

        $this->resolvedItems = $items;

        return $items;
    }

    private function resolveItems(): iterable
    {
        // Priority 1: Direct data
        if (null !== $this->data) {
            return $this->data;
        }

        // Priority 2: Fixture
        if (null !== $this->fixture && null !== $this->fixtureService) {
            return $this->fixtureService->load($this->fixture);
        }

        // Priority 3: HTTP endpoint
        if (null !== $this->endpoint && null !== $this->httpClient) {
            return $this->fetchFromEndpoint();
        }

        return [];
    }

    private function fetchFromEndpoint(): array
    {
        $query = [];
        if (null !== $this->limit) {
            $query['itemsPerPage'] = $this->limit;
        }
        if (null !== $this->offset) {
            $query['page'] = (int) floor($this->offset / ($this->limit ?? 30)) + 1;
        }

        $response = $this->httpClient->request('GET', $this->endpoint, [
            'query' => $query,
        ]);

        $data = $response->toArray();

        // Handle API Platform format
        if (isset($data['hydra:member'])) {
            return $data['hydra:member'];
        }
        if (isset($data['member'])) {
            return $data['member'];
        }

        return $data;
    }

    #[ExposeInTemplate]
    public function getItemCount(): int
    {
        return count($this->getItems());
    }

    #[ExposeInTemplate]
    public function hasItems(): bool
    {
        return $this->getItemCount() > 0;
    }
}
