<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

final class GitHubMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        private readonly string $projectDir,
        private readonly RequestStack $requestStack,
        protected readonly ?IconService $iconService = null,
        /** survos_tabler.yaml's app.links.github — takes priority over .git/config auto-detection (which silently finds nothing once a deploy strips .git, e.g. most buildpack/Dokku slugs). */
        private readonly ?string $githubRepo = null,
    ) {}

    #[AsEventListener(event: MenuEvent::ADMIN_NAVBAR_MENU)]
    public function onAdminNavbarMenu(MenuEvent $event): void
    {
        $this->buildGitHubSubmenu($event);
    }

    /**
     * Same submenu, user-facing — in the always-visible primary navbar dropdown rather than the
     * ROLE_ADMIN-gated admin bar. Filing an issue from wherever you hit a problem, with the page
     * URL already captured, matters most while the app is under active development and everyone
     * hitting it is effectively a tester.
     *
     * NAVBAR_MENU, not NAVBAR_END: the latter renders via navbar_end.html.twig, a template built
     * only for flat single-action icon links (theme toggle, notifications, …) — it prints
     * item.uri directly and never recurses into item.children, so a submenu placed there renders
     * as a single broken href="" link instead of a working dropdown. NAVBAR_MENU (navbar.html.twig)
     * is the slot that actually supports nested children — same one AppMenu's own dropdowns use.
     */
    #[AsEventListener(event: MenuEvent::NAVBAR_MENU)]
    public function onNavbarMenu(MenuEvent $event): void
    {
        // Not "Help" -- host apps commonly already have their own Help item (e.g. openfoto's
        // AppMenu links to a docs site under that exact label) in this same slot; reusing it here
        // would just render two confusingly-identical "Help" entries side by side.
        $this->buildGitHubSubmenu($event);
    }

    private function buildGitHubSubmenu(MenuEvent $event, string $label = 'GitHub'): void
    {
        $repoUrl = $this->githubRepoUrl();
        if (!$repoUrl) {
            return;
        }

        $submenu = $this->addSubmenu($event->getMenu(), $label, 'github');
        $this->add($submenu, uri: $repoUrl, label: 'Repo', icon: 'github', external: true);
        $this->add($submenu, uri: $repoUrl . '/issues', label: 'Issues', icon: 'issues', external: true);
        $this->add($submenu, uri: $this->newIssueUrl($repoUrl), label: 'New issue', icon: 'plus', external: true);
    }

    private function githubRepoUrl(): ?string
    {
        if ($this->githubRepo) {
            return $this->normalizeGitHubUrl($this->githubRepo) ?? rtrim($this->githubRepo, '/');
        }

        $config = $this->gitConfig();
        if (!$config) {
            return null;
        }

        if (!preg_match_all('/^\s*url\s*=\s*(\S+)\s*$/m', $config, $matches)) {
            return null;
        }

        foreach ($matches[1] as $remoteUrl) {
            $repoUrl = $this->normalizeGitHubUrl($remoteUrl);
            if ($repoUrl) {
                return $repoUrl;
            }
        }

        return null;
    }

    private function gitConfig(): ?string
    {
        $gitPath = $this->projectDir . '/.git';
        if (is_file($gitPath . '/config')) {
            return file_get_contents($gitPath . '/config') ?: null;
        }

        if (!is_file($gitPath)) {
            return null;
        }

        $gitDir = trim((string) preg_replace('/^gitdir:\s*/', '', trim((string) file_get_contents($gitPath))));
        if ($gitDir === '') {
            return null;
        }

        if (!str_starts_with($gitDir, '/')) {
            $gitDir = $this->projectDir . '/' . $gitDir;
        }

        foreach ([$gitDir . '/config', $this->commonGitConfig($gitDir)] as $configFile) {
            if ($configFile && is_file($configFile)) {
                return file_get_contents($configFile) ?: null;
            }
        }

        return null;
    }

    private function commonGitConfig(string $gitDir): ?string
    {
        $commonDirFile = $gitDir . '/commondir';
        if (!is_file($commonDirFile)) {
            return null;
        }

        $commonDir = trim((string) file_get_contents($commonDirFile));
        if ($commonDir === '') {
            return null;
        }

        if (!str_starts_with($commonDir, '/')) {
            $commonDir = $gitDir . '/' . $commonDir;
        }

        return $commonDir . '/config';
    }

    private function normalizeGitHubUrl(string $remoteUrl): ?string
    {
        $remoteUrl = preg_replace('/\.git$/', '', trim($remoteUrl)) ?? '';

        if (preg_match('#^git@github\.com:(?<path>[^/]+/[^/]+)$#', $remoteUrl, $matches)) {
            return 'https://github.com/' . $matches['path'];
        }

        if (preg_match('#^ssh://git@github\.com/(?<path>[^/]+/[^/]+)$#', $remoteUrl, $matches)) {
            return 'https://github.com/' . $matches['path'];
        }

        if (preg_match('#^https://github\.com/(?<path>[^/]+/[^/]+)$#', $remoteUrl, $matches)) {
            return 'https://github.com/' . $matches['path'];
        }

        return null;
    }

    private function newIssueUrl(string $repoUrl): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $pageUrl = $request?->getUri();
        $body = $pageUrl ? "Page: {$pageUrl}\n\nProblem:\n" : "Problem:\n";

        return $repoUrl . '/issues/new?' . http_build_query([
            'title' => 'Issue from ' . ($request?->getPathInfo() ?: 'admin'),
            'body' => $body,
        ]);
    }
}
