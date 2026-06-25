<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Renders the project's docs/*.md files as in-app pages (the nested menu is built by
 * {@see \Survos\TablerBundle\Menu\DocsMenuSubscriber}). Markdown → HTML via the markdown_to_html
 * Twig filter. Docs rarely change, so responses are HTTP-cached.
 */
final class DocsController extends AbstractController
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {}

    #[Route('/docs/{path}', name: 'survos_tabler_doc', requirements: ['path' => '.+'], defaults: ['path' => 'README'])]
    #[Cache(public: true, maxage: 3600, smaxage: 3600)]
    public function show(string $path): Response
    {
        $docsDir = realpath($this->projectDir . '/docs');
        if ($docsDir === false) {
            throw $this->createNotFoundException('No docs directory.');
        }

        // Resolve under docs/ and reject traversal / non-.md targets.
        $file = realpath($docsDir . '/' . $path . '.md');
        if ($file === false || !str_starts_with($file, $docsDir . '/') || !is_file($file)) {
            throw $this->createNotFoundException(sprintf('Doc not found: %s', $path));
        }

        return $this->render('@SurvosTabler/docs/show.html.twig', [
            'markdown' => (string) file_get_contents($file),
            'path' => $path,
            'title' => $this->titleFromPath($path),
        ]);
    }

    private function titleFromPath(string $path): string
    {
        $base = basename($path);

        return ucwords(str_replace(['-', '_'], ' ', $base));
    }
}
