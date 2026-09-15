<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

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
        private readonly Environment $twig,
    ) {}

    /** twig/markdown-extra is only suggested; without it, show the markdown as text rather than 500. */
    private function template(): string
    {
        try {
            // twig/extra-bundle throws a SyntaxError (with an install hint) for a missing filter.
            $available = $this->twig->getFilter('markdown_to_html') !== null;
        } catch (\Twig\Error\SyntaxError) {
            $available = false;
        }

        return $available ? '@SurvosTabler/docs/show.html.twig' : '@SurvosTabler/docs/show_plain.html.twig';
    }

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
            // /docs with no docs/README.md: list what is there rather than 404 the entry point.
            if ($path === 'README') {
                return $this->render($this->template(), [
                    'markdown' => $this->indexMarkdown($docsDir),
                    'path' => $path,
                    'title' => 'Docs',
                ]);
            }
            throw $this->createNotFoundException(sprintf('Doc not found: %s', $path));
        }

        return $this->render($this->template(), [
            'markdown' => (string) file_get_contents($file),
            'path' => $path,
            'title' => $this->titleFromPath($path),
        ]);
    }

    private function indexMarkdown(string $docsDir): string
    {
        $paths = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($docsDir, \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'md') {
                $paths[] = substr($file->getPathname(), strlen($docsDir) + 1, -3);
            }
        }
        sort($paths);

        $lines = ['# Docs', ''];
        foreach ($paths as $docPath) {
            $lines[] = sprintf('- [%s](%s)', $this->titleFromPath($docPath), $this->generateUrl('survos_tabler_doc', ['path' => $docPath]));
        }

        return implode("\n", $paths === [] ? ['# Docs', '', 'No documents yet.'] : $lines);
    }

    private function titleFromPath(string $path): string
    {
        $base = basename($path);

        return ucwords(str_replace(['-', '_'], ' ', $base));
    }
}
