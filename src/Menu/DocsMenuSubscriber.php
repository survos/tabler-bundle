<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Knp\Menu\ItemInterface;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface as CacheItem;

/**
 * Adds a "Docs" dropdown that mirrors the project's docs/ directory: every .md becomes a menu item
 * (rendered by {@see \Survos\TablerBundle\Controller\DocsController}), subdirectories become nested
 * submenus. The filesystem scan is heavily cached (docs rarely change) — only the cheap menu build
 * runs per request.
 */
final class DocsMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        private readonly CacheInterface $cache,
        protected readonly ?IconService $iconService = null,
    ) {}

    #[AsEventListener(event: MenuEvent::ADMIN_NAVBAR_MENU)]
    public function onAdminNavbarMenu(MenuEvent $event): void
    {
        $tree = $this->docsTree();
        if ($tree['files'] === [] && $tree['dirs'] === []) {
            return;
        }

        $submenu = $this->addSubmenu($event->getMenu(), 'Docs', 'document');
        $this->buildMenu($submenu, $tree);
    }

    /** @return array{dirs: array<string,array>, files: list<array{path:string,label:string}>} */
    private function docsTree(): array
    {
        return $this->cache->get('survos_tabler_docs_tree', function (CacheItem $item): array {
            $item->expiresAfter(3600);

            return $this->scan($this->projectDir . '/docs', '');
        });
    }

    /** @return array{dirs: array<string,array>, files: list<array{path:string,label:string}>} */
    private function scan(string $absDir, string $relPrefix): array
    {
        $dirs = [];
        $files = [];
        foreach (is_dir($absDir) ? (scandir($absDir) ?: []) : [] as $entry) {
            if ($entry === '.' || $entry === '..' || str_starts_with($entry, '.')) {
                continue;
            }
            $abs = $absDir . '/' . $entry;
            $rel = ltrim($relPrefix . '/' . $entry, '/');
            if (is_dir($abs)) {
                $sub = $this->scan($abs, $rel);
                if ($sub['dirs'] !== [] || $sub['files'] !== []) {
                    $dirs[$entry] = $sub;
                }
            } elseif (str_ends_with(strtolower($entry), '.md')) {
                $name = substr($entry, 0, -3);
                $files[] = [
                    'path' => ltrim($relPrefix . '/' . $name, '/'),
                    'label' => $this->label($name),
                ];
            }
        }

        // README first, then the rest alphabetically by label.
        usort($files, static fn(array $a, array $b): int =>
            [strcasecmp($a['path'], 'README'), $a['label']] <=> [strcasecmp($b['path'], 'README'), $b['label']]);
        ksort($dirs);

        return ['dirs' => $dirs, 'files' => $files];
    }

    /** @param array{dirs: array<string,array>, files: list<array{path:string,label:string}>} $tree */
    private function buildMenu(ItemInterface $menu, array $tree): void
    {
        foreach ($tree['files'] as $file) {
            $this->add($menu, route: 'survos_tabler_doc', rp: ['path' => $file['path']], label: $file['label'], icon: 'document');
        }
        foreach ($tree['dirs'] as $name => $sub) {
            $child = $this->addSubmenu($menu, $this->label($name), 'folder');
            $this->buildMenu($child, $sub);
        }
    }

    private function label(string $name): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $name));
    }
}
